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
    const REDIS_APP_CHANNEL_VERSIONS = 'app_%s_channel_%s_versions';

    /**
     * 版本缓存过期时间
     */
    const REDIS_APP_CHANNEL_VERSIONS_EXPIRE = 60 * 60;

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
     * 删除 app 版本控制缓存
     *
     * @param int $appId 应用id
     * @param int $channelId 渠道id
     * @return bool
     */
    public function delRedisVersion(int $appId = 0, int $channelId = 0)
    {
        if ($appId && $channelId) {
            $redisKey = sprintf(ChannelVersion::REDIS_APP_CHANNEL_VERSIONS, $appId, $channelId);
            yii::$app->redis->del($redisKey);
            return true;
        }
        if (!$channelId) {
            $channels = Channel::findAll(['is_del' => Channel::NOT_DELETED, 'status' => Channel::ACTIVE_STATUS]);
            if (!empty($channels)) {
                foreach ($channels as $channel) {
                    $redisKey = sprintf(ChannelVersion::REDIS_APP_CHANNEL_VERSIONS, $appId, $channel->id);
                    yii::$app->redis->del($redisKey);
                }
            }
            return true;
        }
        if (!$appId) {
            $apps = App::findAll(['is_del' => App::NOT_DELETED]);
            if (!empty($apps)) {
                foreach ($apps as $app) {
                    $redisKey = sprintf(ChannelVersion::REDIS_APP_CHANNEL_VERSIONS, $app->id, $channelId);
                    yii::$app->redis->del($redisKey);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * 获得最新的版本信息
     *
     * @param $model
     * @return array
     */
    public function getLatest($model)
    {
        // 查询缓存
        $redisKey = sprintf(ChannelVersion::REDIS_APP_CHANNEL_VERSIONS, $model->app_id, $model->channel);

        $result = yii::$app->redis->get($redisKey);

        // 查询到缓存则寻找匹配版本信息进行返回
        if ($result) {
            // 安卓如果渠道不存在则调用官方包更新
            if ($result == ChannelVersion::CHANNEL_NOT_EXIST) {
                $model->channel = Channel::ANDROID_OFFICIAL;
                return $this->getLatest($model);
            }

            if ($result != ChannelVersion::APP_VERSION_NOT_EXIST && ($versions = json_decode($result, true))) {
                // 按照版本号排序并比较出最新的版本
                $versions = array_filter($versions, function ($val) use ($model) {
                    return ($val['platform'] == $model->platform);
                });
                if (!empty($versions)) {
                    array_multisort(array_column($versions, 'name'), SORT_DESC, $versions);
                    foreach ($versions as $version) {
                        if (Version::versionNameToCode($model->name) >= Version::versionNameToCode($version['min_name']) ?? 0) {
                            switch ($version['scope']) {
                                case Version::SCOPE_ALL:
                                    $version['is_update'] = true;
                                    return $this->transformers($version);
                                    break;
                                case Version::SCOPE_IP:
                                    if (App::scopeIpStatus($model->app_id)) {
                                        $version['is_update'] = true;
                                        return $this->transformers($version);
                                    } else {
                                        // 不在 ip 更新范围内
                                        return $this->transformers();
                                    }
                                    break;
                                default:
                                    $version['is_update'] = true;
                                    return $this->transformers($version);
                            }
                        }
                    }
                }
            }
            // 不存在或者没有版本信息给出基本返回模板
            return $this->transformers();
        } else {
            // 查询不到缓存则查数据库
            // 根据 app_id 和 channel_id 用 Version joinWith ChannelVersion 查出对应应用与渠道所有版本信息
            $versions = Version::find()
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
                    Version::tableName() . '.status' => Version::STATUS_ON,
                    Version::tableName() . ".is_del" => Version::NOT_DELETED,
                    Channel::tableName() . ".status" => Channel::ACTIVE_STATUS,
                    ChannelVersion::tableName() . ".channel_id" => $model->channel
                ])
                ->asArray()
                ->all();

            // 将版本信息入 redis 并回调 getLatest 进行return
            if ($versions) {
                yii::$app->redis->set($redisKey, json_encode($versions));
            } elseif (($model->platform == App::ANDROID)
                && ($model->channel != Channel::ANDROID_OFFICIAL)
                && App::findOne(['is_del' => App::NOT_DELETED, 'id' => $model->app_id])
                && !Channel::findOne(['is_del' => Channel::NOT_DELETED, 'id' => $model->channel, 'status' => Channel::ACTIVE_STATUS])) {
                // 如果是 Android 请求的不是官方包 存在 app 但不存在 channel 的，调用官方包下载
                yii::$app->redis->set($redisKey, ChannelVersion::CHANNEL_NOT_EXIST);
            } else {
                // 不存在的 app_id & channel_id 则缓存一个模板版本信息（预防缓存穿透）
                yii::$app->redis->set($redisKey, ChannelVersion::APP_VERSION_NOT_EXIST);
            }
            // 预防缓存雪崩让过期时间加一个随机值
            yii::$app->redis->expire($redisKey, ChannelVersion::REDIS_APP_CHANNEL_VERSIONS_EXPIRE + rand(0, 60 * 60));

            return $this->getLatest($model);
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
        (new ChannelVersion())->delRedisVersion(0, $this->channel_id);
    }
}
