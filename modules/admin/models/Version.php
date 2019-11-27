<?php

namespace yiiplus\appversion\modules\admin\models;

use common\models\system\AdminUser;
use Yii;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Channel;
use yiiplus\appversion\modules\admin\models\ChannelVersion;

/**
 * This is the model class for table "yp_appversion_version".
 *
 * @property int $id 主键id
 * @property int $app_id 应用关联id
 * @property int $code 版本号 7152
 * @property int $min_code 最小版本号 7000
 * @property string $name 版本号 格式 1.1.1
 * @property string $min_name 最小版本号 格式 1.1.1
 * @property int $type 更新类型 1 一般更新 2 强制更新 3 静默更新 4 可忽略更新 5 静默可忽略更新
 * @property int $platform 平台 1 iOS 2 安卓 
 * @property int $scope 发布范围（1 全量、2 白名单、3 ip发布）
 * @property string $desc 版本描述 最长字符
 * @property int $status 发布范围（1 全量、2 白名单、3 ip发布）
 * @property int $operated_id 用户id
 * @property int $is_del 状态；0正常；1主动删除；2后台删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $deleted_at 删除时间
 */
class Version extends ActiveRecord
{
    const UPDATE_TYPE = [
        1 => '一般更新',
        2 => '强制更新',
        3 => '静默更新',
    ];

    const SCOPE_TYPE = [
        1 => '全量更新',
        2 => '白名单'
    ];

    const STATUS_TYPE = [
        1 => '上架',
        2 => '下架'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yp_appversion_version';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id', 'code', 'min_code', 'name', 'min_name', 'type', 'scope', 'platform'], 'required'],
            [['app_id', 'code', 'min_code', 'type', 'platform', 'scope', 'status', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['desc'], 'string'],
            [['name', 'min_name'], 'match', 'pattern'=>'/^[1-9]\d*\.[0-9]\d*\.[0-9]\d*$/', 'message'=>'格式形如为 1.1.1'],
            [['name', 'min_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => '所属应用',
            'code' => '版本号',
            'min_code' => '最小版本号',
            'name' => '版本名',
            'min_name' => '最小版本名',
            'type' => '更新类型',
            'platform' => '平台',
            'scope' => '发布范围',
            'desc' => '版本描述',
            'status' => '上架状态',
            'operated_id' => '操作人 ID',
            'operator' => '操作人',
            'is_del' => 'Is Del',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * 应用关联
     */
    public function getApp()
    {
        return $this->hasOne(App::className(), ['id' => 'app_id']);
    }

    /**
     * 渠道关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannelVersions()
    {
        return $this->hasMany(ChannelVersion::className(), ['version_id' => 'id']);
    }

    /**
     * 渠道关联
     */
    public function getChannels()
    {
        return $this->hasMany(Channel::className(), ['id' => 'channel_id'])
            ->via('channelVersions');
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

    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->operated_id = Yii::$app->user->id;

            } else {
                $this->operated_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }
}
