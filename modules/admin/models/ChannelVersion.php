<?php
/**
 * 萌股 - 二次元潮流聚集地
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */

namespace yiiplus\appversion\modules\admin\models;

use common\models\system\AdminUser;
use Yii;
use yii\db\ActiveQuery;

/**
 * ChannelVersion 渠道包模型
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 *
 * @property int $id 主键id
 * @property int $version_id 版本关联id
 * @property int $channel_id 渠道主键id
 * @property string $url 安卓对应该渠道的 APK 下载地址， iOS 为 appstore 地址
 * @property int $operated_id 用户id
 * @property int $is_del 状态；0正常；1主动删除；2后台删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $deleted_at 删除时间
 */
class ChannelVersion extends ActiveRecord
{
    /**
     * 上传路径 上传路径
     */
    const UPLOAD_APK_DIR = 'version/apk';

    /**
     * redis 根据 app_id 和 channel_id 保存版本信息
     */
    const REDIS_APP_VERSION = 'app_%s_platform_%s_channel_%s';

    /**
     * 版本缓存过期时间
     */
    const REDIS_APP_CHANNEL_VERSIONS_EXPIRE = 12 * 60 * 60;

    /**
     * APP 或者版本信息不存在
     */
    const APP_VERSION_NOT_EXIST = -1;

    /**
     * 渠道 不存在
     */
    const CHANNEL_NOT_EXIST = -2;

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return 'yp_appversion_channel_version';
    }

    /**
     * 基本规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['version_id', 'channel_id'], 'unique', 'targetAttribute' => ['version_id', 'channel_id', 'deleted_at']],
            [['version_id', 'channel_id'], 'required'],
            [['version_id', 'channel_id', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * 字段中文名
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'app' => '应用',
            'version_id' => '版本',
            'channel_id' => '渠道',
            'url' => '链接地址',
            'operated_id' => '操作人',
            'is_del' => 'Is Del',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * 渠道关联
     *
     * @return ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channel_id']);
    }

    /**
     * 版本关联
     *
     * @return ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(Version::className(), ['id' => 'version_id']);
    }

    /**
     * 根据版本获取最新的版本信息
     *
     * @param $model
     * @return array
     */
    public function getLatest($model)
    {
        // 条件：app_id、platform、channel、name
        // 查询缓存
        $redisKey = sprintf(ChannelVersion::REDIS_APP_VERSION, $model->app_id, $model->platform, $model->channel);
        $version = Yii::$app->redis->hget($redisKey, $model->name);

        if ($version == ChannelVersion::APP_VERSION_NOT_EXIST) {
            return $this->transformers();
        }

        // 缓存中输出结果
        if ($version) {
            $version = json_decode($version, true);
            return $this->getVersionData($version);
        }

        // 查询数据库
        $versions = $this->getVersions($model);

        // 如果是安卓，且没有当前的版本信息，调用官方包
        if (!$versions && $model->platform == App::ANDROID) {
            $model->channel = Channel::ANDROID_OFFICIAL;
            $versions = $this->getVersions($model);
        }

        // 比较版本，得出设备 version_name 的结果
        array_multisort(array_column($versions, 'name'), SORT_DESC, $versions);
        foreach ($versions as $v) {
            if (Version::versionNameToCode($model->name) >= Version::versionNameToCode($version['min_name']) ?? 0) {
                yii::$app->redis->hset($redisKey, $model->name, json_encode($v));
                yii::$app->redis->expire($redisKey, ChannelVersion::REDIS_APP_CHANNEL_VERSIONS_EXPIRE);
                return $this->getVersionData($v);
            }
        }

        // 没有合适的结果则存入-1，避免缓存穿透问题
        yii::$app->redis->hset($redisKey, $model->name, ChannelVersion::APP_VERSION_NOT_EXIST);
        yii::$app->redis->expire($redisKey, ChannelVersion::REDIS_APP_CHANNEL_VERSIONS_EXPIRE);
        return  $this->transformers();
    }

    /**
     * 查询数据库
     *
     * @param $model
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVersions($model)
    {
        // 查询数据库
        return Version::find()
            ->joinWith(['channelVersions', 'channels'], false)
            ->select([
                Version::tableName() . '.*',
                ChannelVersion::tableName() . '.channel_id',
                ChannelVersion::tableName() . '.version_id',
                ChannelVersion::tableName() . '.url',
                Channel::tableName() . '.status',
            ])
            ->where([
                Version::tableName() . ".app_id" => $model->app_id,
                Version::tableName() . ".platform" => $model->platform,
                Version::tableName() . '.status' => Version::STATUS_ON,
                Version::tableName() . ".is_del" => Version::NOT_DELETED,
                Channel::tableName() . ".status" => Channel::ACTIVE_STATUS,
                Channel::tableName() . ".is_del" => Channel::NOT_DELETED,
                ChannelVersion::tableName() . ".channel_id" => $model->channel,
                ChannelVersion::tableName() . ".is_del" => ChannelVersion::NOT_DELETED
            ])
            ->asArray()
            ->all();
    }

    /**
     * 组装数据
     *
     * @param $version
     * @return array
     */
    public function getVersionData($version)
    {
        // 更新范围：ip白名单、全量
        switch ($version['scope']) {
            case Version::SCOPE_ALL:
                $version['is_update'] = Version::ALLOW_UPDATE;
                return $this->transformers($version);
                break;
            case Version::SCOPE_IP:
                if ((new App)->scopeIpStatus($version['app_id'])) {
                    $version['is_update'] = Version::ALLOW_UPDATE;
                    return $this->transformers($version);
                } else {
                    // 不在 ip 更新范围内
                    return $this->transformers();
                }
                break;
            default:
                $version['is_update'] = Version::ALLOW_UPDATE;
                return $this->transformers($version);
        }
    }

    /**
     * 清除该应用和渠道对应的版本
     *
     * @param $appId
     * @param $channelId
     * @param $version
     */
    public function unsetRedisVersion($appId = 0, $channelId = 0, $version = '')
    {
        // 删除某个app 某个渠道
        if ($appId && $channelId) {
            if ($channelId == Channel::IOS_OFFICIAL) {
                $redisKeyToIos = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::IOS, $channelId);
                yii::$app->redis->del($redisKeyToIos);
            } else {
                $redisKeyToAndroid = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::ANDROID, $channelId);
                yii::$app->redis->del($redisKeyToAndroid);
            }
        }

        // 删除该应用所有 appId
        if ($appId && !$channelId) {
            $app = App::findOne($appId);
            $versions = $app->getVersions()->with('channels')->all();
            foreach ($versions as $version) {
                $channels = $version->channels;
                foreach ($channels as $channel) {
                    if ($channel->id == Channel::IOS_OFFICIAL) {
                        $redisKeyToIos = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::IOS, $channel->id);
                        yii::$app->redis->del($redisKeyToIos);
                    } else {
                        $redisKeyToAndroid = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::ANDROID, $channel->id);
                        yii::$app->redis->del($redisKeyToAndroid);
                    }
                }
            }
        }

        // 删除该渠道所有 channelId
        if (!$appId && $channelId) {
            $channel = Channel::findOne($channelId);
            $versions = $channel->getVersions()->with('channelVersions')->where(['is_del' => Version::NOT_DELETED])->all();
            foreach ($versions as $version) {
                $app = $version->app;
                if ($channelId == Channel::IOS_OFFICIAL) {
                    $redisKeyToIos = sprintf(ChannelVersion::REDIS_APP_VERSION, $app->id, App::IOS, $channelId);
                    yii::$app->redis->del($redisKeyToIos);
                } else {
                    $redisKeyToAndroid = sprintf(ChannelVersion::REDIS_APP_VERSION, $app->id, App::ANDROID, $channelId);
                    yii::$app->redis->del($redisKeyToAndroid);
                }
            }
        }

        // 删除某个渠道的某个版本
        if ($appId && $channelId && $version) {
            if ($channelId == Channel::IOS_OFFICIAL) {
                $redisKeyToIos = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::IOS, $channelId);
                yii::$app->redis->hdel($redisKeyToIos, $version);
            } else {
                $redisKey = sprintf(ChannelVersion::REDIS_APP_VERSION, $appId, App::ANDROID, $channelId);
                yii::$app->redis->hdel($redisKey, $version);
            }
        }
    }

    /**
     * 管理员关联
     *
     * @return ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'operated_id']);
    }

    /**
     * 接口结果转换
     *
     * @param array $data
     * @return array
     */
    public function transformers($data = [])
    {
        $version_info = [
            'name' => $data['name'] ?? "0.0.0",
            'is_update' => $data['is_update'] ?? false,
            'type' => $data['type'] ?? 1,
            'scope' => $data['scope'] ?? 1,
            'desc' => $data['desc'] ?? '',
            'url' => $data['url'] ?? ''
        ];
        return $version_info;
    }

    /**
     * 模型监控器
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (!$this->isNewRecord) {
                //软删除
                if ($this->is_del == self::ACTIVE_DELETE) {
                    $this->deleted_at = time();
                }
            }
            $this->operated_id = Yii::$app->user->id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存以后更新缓存
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        (new ChannelVersion())->unsetRedisVersion($this->version->app_id, $this->channel_id);
    }
}
