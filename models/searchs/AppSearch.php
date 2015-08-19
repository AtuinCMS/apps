<?php
namespace atuin\apps\models\searchs;


use atuin\apps\models\App;
use yii\data\ActiveDataProvider;

class AppSearch extends App
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string'],
            [['id', 'name', 'version', 'install_date', 'description'], 'safe'],
        ];
    }

    /**
     * Search
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = App::find();

        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'version' => $this->version,
            'install_date' => $this->install_date,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}