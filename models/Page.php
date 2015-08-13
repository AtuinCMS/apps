<?php
namespace atuin\apps\models;


use yii\db\ActiveRecord;


/**
 * Class Page
 * @package atuin\apps\models
 *
 * @property int $id
 * @property string $name
 * @property string $type
 *
 * This field will be a json string with the form
 *
 *      json_encode([
 *                      [
 *                          'class' => Model::className(),
 *                          'field' => 'fieldName' (if not defined, then will be stored as "id")
 *                      ]
 *                  ])
 *
 * @property string $parameters
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'pages';
    }
}