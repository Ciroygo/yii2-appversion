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
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "yp_appversion_app".
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'yp_appversion_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'application_id'], 'required'],
            [['is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['application_id'], 'string', 'max' => 255],
            ['application_id', 'match', 'pattern'=>'/^[a-zA-Z][a-zA-Z0-9_.]{4,29}$/', 'message'=>'5-30位字母、数字或“_”“.”, 字母开头']
        ];
    }
//
//    public function attributes() {
//        return array_merge(parent::attributes(), git['operator']);
//    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '应用名',
            'application_id' => '应用 Id',
            'operated_id' => '操作人 Id',
            'operator' => '操作人',
            'is_del' => '是否删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'deleted_at' => '删除时间',
        ];
    }

    public function getOperator()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'operated_id']);
    }

    /**
     * 白名单查询
     *
     * @param array  $params 参数
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $query->where([self::tableName() . '.is_del' => self::NOT_DELETED]);

        $this->load($params, null);
        return $dataProvider;
    }

    /**
     * 版本关联
     */
    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['app_id' => 'id']);
    }
}
