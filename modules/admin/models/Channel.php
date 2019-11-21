<?php

namespace yiiplus\appversion\modules\admin\models;

use Yii;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
use yiiplus\appversion\modules\admin\models\Version;

/**
 * This is the model class for table "yp_appversion_channel".
 *
 * @property int $id 主键id
 * @property string $name 渠道名称
 * @property int $platform 平台 1 iOS 2 安卓
 * @property string $code 渠道码 安卓官方包渠道码为official，其他渠道则另加，iOS仅有一个渠道为 official
 * @property int $status 状态 1 正常 2 废弃
 * @property int $operated_id 用户id
 * @property int $is_del 状态；0正常；1主动删除；2后台删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $deleted_at 删除时间
 */
class Channel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yp_appversion_channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform', 'status', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'platform' => 'Platform',
            'code' => 'Code',
            'status' => 'Status',
            'operated_id' => 'Operated ID',
            'is_del' => 'Is Del',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    public function getChannelVersions()
    {
        return $this->hasMany(ChannelVersion::className(), ['channel_id' => 'id']);
    }

    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['id' => 'version_id'])
            ->via('channelVersions');
    }
}
