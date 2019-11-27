<?php

namespace yiiplus\appversion\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yiiplus\appversion\modules\admin\models\ChannelVersion;

/**
 * ChannelVersionSearch represents the model behind the search form of `yiiplus\appversion\modules\admin\models\ChannelVersion`.
 */
class ChannelVersionSearch extends ChannelVersion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'version_id', 'channel_id', 'operated_id', 'is_del', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['url'], 'safe'],
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
        $query = ChannelVersion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'version_id' => $this->version_id,
            'channel_id' => $this->channel_id,
            'operated_id' => $this->operated_id,
            'is_del' => $this->is_del,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url]);

        $query->andWhere(['is_del' => self::NOT_DELETED]);

        return $dataProvider;
    }
}
