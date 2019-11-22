<?php

namespace yiiplus\appversion\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yiiplus\appversion\modules\admin\models\App;

/**
 * AppSearch represents the model behind the search form of `yiiplus\appversion\modules\admin\models\App`.
 */
class AppSearch extends App
{
    public $operator;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['name', 'application_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = App::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

//        $query->where([self::tableName() . '.is_del' => self::NOT_DELETED]);

        $this->load($params, null);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'operated_id' => $this->operated_id,
            'is_del' => $this->is_del,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'application_id', $this->application_id]);

        return $dataProvider;
    }
}
