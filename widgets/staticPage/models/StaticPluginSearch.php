<?php
namespace atuin\apps\widgets\staticPage\models;

use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

/**
 * Class StaticPluginSearch
 * @package atuin\apps\widgets\staticPage\models
 *
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $text
 * @property string $creation_date
 * @property string $update_date
 * @property int $author_id
 * @property int $last_editor_id
 * @property string $author
 * @property string $lastEditor
 */
class StaticPluginSearch extends StaticPlugin
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'last_editor_id'], 'integer'],
            [['title', 'url', 'text', 'creation_date', 'update_date'], 'string'],
            [['id', 'author_id', 'author', 'lastEditor', 'last_editor_id', 'title', 'url', 'text', 'creation_date', 'update_date'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['author', 'lastEditor']);
    }

    /**
     * Search
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // Adding inner joins to be able to search authors and editors in the gridView

        $query = StaticPlugin::find()->with('author', 'lastEditor')->
            innerJoin('user as user_author', 'user_author.id = ' . $this->tableName() . '.author_id')->
            innerJoin('user as user_editor', 'user_editor.id = ' . $this->tableName() . '.last_editor_id');
        
        // create data provider
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // Enable sorting for the related columns

        $addSortAttributes = ['author', 'lastEditor'];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc' => [$addSortAttribute => SORT_ASC],
                'desc' => [$addSortAttribute => SORT_DESC],
            ];
        }


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'last_editor_id' => $this->last_editor_id,
            'creation_date' => $this->creation_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'url', $this->url]);

        $query->andFilterWhere(['like', 'user_author.username', $this->author]);
        $query->andFilterWhere(['like', 'user_editor.username', $this->lastEditor]);
        
        return $dataProvider;
    }
}