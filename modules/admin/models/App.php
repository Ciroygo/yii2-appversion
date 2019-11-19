<?php

namespace yiiplus\appversion\modules\admin\models;

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
            [['operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['application_id'], 'string', 'max' => 255],
            [['operated_id'], 'unique'],
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
            'application_id' => '应用 Id',
            'operated_id' => '操作人 ID',
            'is_del' => '是否删除',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
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
                'pageSize' => 2,
            ],
        ]);

        $query->where([self::tableName() . '.is_del' => self::NOT_DELETED]);

        $this->load($params, null);
        return $dataProvider;
    }
}
