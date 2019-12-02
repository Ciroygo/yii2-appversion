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

use Yii;
use common\models\system\AdminUser;

/**
 * Channel 模型基类
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
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
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return 'yp_appversion_channel';
    }

    /**
     * 基本规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['platform', 'name', 'code'],'required'],
            [['platform', 'status', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 64],
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
            'id' => 'ID',
            'name' => '渠道名',
            'platform' => '平台',
            'code' => '渠道码',
            'status' => 'Status',
            'operated_id' => 'Operated ID',
            'is_del' => 'Is Del',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * 渠道包关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChannelVersions()
    {
        return $this->hasMany(ChannelVersion::className(), ['channel_id' => 'id']);
    }

    /**
     * 版本关联
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['id' => 'version_id'])
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

    /**
     * 前端下拉选项获取
     *
     * @param $platform
     * @param bool $version
     * @return array|false
     */
    public static function getChannelOptions($platform, $version = false)
    {
        $query = self::find()->select(['id', 'name'])->where(['platform' => $platform])->andWhere(['status' => 1])->andWhere(['is_del' => self::NOT_DELETED]);

        // 已经新增的渠道不显示在下拉框中
        if ($version) {
            $exists_channels = $version->getChannels()->select(['id'])->column();
            if (!empty($exists_channels)) {
                $query->andWhere(['not in', 'id', $exists_channels]);
            }
        }
        $channels = $query->asArray()->all();
        return array_combine(array_column($channels, 'id'), array_column($channels, 'name'));
    }

    /**
     * 保存前处理
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->status = 0;
                $this->operated_id = Yii::$app->user->id;
            } else {
                $this->operated_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存后处理
     *
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool|void
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->status = 1;
                $this->operated_id = Yii::$app->user->id;
            } else {
                // 软删除
                if ($this->is_del == self::ACTIVE_DELETE) {
                    ChannelVersion::updateAll(['is_del' => self::ACTIVE_DELETE], ['channel_id' => $this->id]);
                }
                $this->operated_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }
}
