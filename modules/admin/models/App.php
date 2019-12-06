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
 * App 模型基类
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 *
 * @property int $id 主键id
 * @property string $name 应用名称
 * @property string $application_id 应用名称
 * @property int $operated_id 用户id
 * @property int $is_del 状态；0正常；1主动删除；2后台删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $deleted_at 删除时间
 */
class App extends ActiveRecord
{
    /**
     * PLATFORM_OPTIONS APP的类型
     */
    const PLATFORM_OPTIONS = [
        self::ANDROID => 'Android',
        self::IOS => 'iOS'
    ];

    /**
     * ANDROID 安卓
     */
    const ANDROID = 2;

    /**
     * IOS ios
     */
    const IOS = 1;

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return 'yp_appversion_app';
    }

    /**
     * 基本规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['application_id', 'unique'],
            [['name', 'application_id'], 'required'],
            [['is_del', 'created_at', 'updated_at', 'deleted_at', 'operated_id'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['application_id'], 'string', 'max' => 255],
            ['application_id', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9_.]{4,29}$/', 'message'=>'5-30位字母、数字或“_”“.”, 字母开头']
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
            'name' => '应用名称',
            'application_id' => '应用KEY',
            'operated_id' => '操作人',
            'operator' => '操作人',
            'is_del' => 'Is Del',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * 下拉框获取 APP 选项
     *
     * @return array|false
     */
    public static function getAppOptions()
    {
        $channels = self::find()->select(['id', 'name'])->where(['is_del' => self::NOT_DELETED])->asArray()->all();
        return array_combine(array_column($channels, 'id'), array_column($channels, 'name'));
    }

    /**
     * 版本关联
     *
     * @return ActiveQuery
     */
    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['app_id' => 'id']);
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
     * 模型监控器
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->operated_id = Yii::$app->user->id;
            } else {
                if ($this->is_del == self::ACTIVE_DELETE) {
                    $version_ids = $this->getVersions()->select(['id'])->column();
                    ChannelVersion::updateAll(['is_del' => self::ACTIVE_DELETE], ['in', 'version_id', $version_ids]);
                    Version::updateAll(['is_del' => self::ACTIVE_DELETE], ['app_id' => $this->id]);
                }
                $this->operated_id = Yii::$app->user->id;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 保存后操作
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        (new ChannelVersion())->delRedisVersion($this->id);
    }
}
