<?php
namespace atuin\apps\models\searchs;


use atuin\apps\models\App;
use yii\data\ActiveDataProvider;

class AppSearch extends App
{
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
        
        return $dataProvider;
    }
}