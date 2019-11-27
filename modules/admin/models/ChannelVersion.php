<?php

namespace yiiplus\appversion\modules\admin\models;

use common\models\system\AdminUser;
use Yii;

/**
 * This is the model class for table "yp_appversion_channel_version".
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
    public $code;
    public $name;
    public $app_id;
    public $platform;
    public $channel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yp_appversion_channel_version';
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'app' => '应用',
            'version_id' => '版本',
            'channel_id' => '渠道',
            'url' => '链接地址',
            'operated_id' => 'Operated ID',
            'is_del' => 'Is Del',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * 渠道关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channel_id']);
    }

    /**
     * 版本关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(Version::className(), ['id' => 'version_id']);
    }

    public function latest($model)
    {
        $app = App::findOne($model->app_id);
        if (!$app) {
            // 默认给客户端一个不报错的默认更新信息
            return $this->transformers();
        }

        // 取得应用的所有版本 id，根据 where in 与 channel_id 查询所属渠道最新版本数据
        $versions_arr = $app->getVersions()->select(['id'])->where(['app_id' => $model->app_id])->asArray()->all();
        $versionIds = array_column($versions_arr,'id');
        if (empty($versionIds)) {
            return $this->transformers();
        }

        $channelVersions = ChannelVersion::find()
            ->joinWith('version')
            ->where(['channel_id' => $model->channel])
            ->andWhere([Version::tableName() . '.platform' => $model->platform])
            ->andWhere(['in', 'version_id', $versionIds])
            ->orderBy([Version::tableName() . '.code' => SORT_DESC])
            ->all();

        if (empty($channelVersions)) {
            return $this->transformers();
        }

        foreach ($channelVersions as $channelVersion) {
            if ($model->code > $channelVersion->version->min_code ?? 0) {
                return $this->transformers($channelVersion);
                break;
            }
        }

        return $this->transformers();
    }

    public function transformers($data = false)
    {
        $version_info = [
            'code' => $data->version->code ?? 0,
            'min_code' => $data->version->min_code ?? 0,
            'name' => $data->version->name ?? "0.0.0",
            'min_name' => $data->version->min_name ?? "0.0.0",
            'type' => $data->version->type ?? 1,
            'scope' => $data->version->scope ?? 1,
            'desc' => $data->version->desc ?? '',
            'url' => $data->url ?? ''
        ];
        return $version_info;
    }

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            $this->operated_id = Yii::$app->user->id;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 管理员关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'operated_id']);
    }
}
