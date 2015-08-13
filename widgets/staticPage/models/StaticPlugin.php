<?php


namespace atuin\apps\widgets\staticPage\models;


use amnah\yii2\user\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\validators\Validator;


/**
 * Class StaticPlugin
 * @package atuin\apps\models
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $text
 * @property string $creation_date
 * @property string $update_date
 * @property int $author_id
 * @property int $last_editor_id
 */
class StaticPlugin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'static_plugin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        Validator::$builtInValidators['uri'] = 'cyneek\yii2\widget\urlparser\validators\UriValidator';
        
        return [
            [['title', 'url', 'text'], 'required'],
            [['id', 'author_id', 'last_editor_id'], 'integer'],
            [['creation_date', 'update_date'], 'string'],
            [['text'], 'string', 'encoding' => 'utf8'],
            [['title'], 'string', 'length' => [5, 255]],
            [['url'], 'uri'],
            [
                ['text'], function ($attribute) {
                // Maybe adding
                $this->$attribute = \yii\helpers\HtmlPurifier::process($this->$attribute);
            }
            ],
            [['id', 'title', 'url', 'text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'id'),
            'title' => Yii::t('admin', 'Title'),
            'url' => Yii::t('admin', 'Url'),
            'text' => Yii::t('admin', 'Text'),
            'creation_date' => Yii::t('admin', 'Creation date'),
            'update_date' => Yii::t('admin', 'Last Update'),
            'author_id' => Yii::t('admin', 'Author'),
            'last_editor_id' => Yii::t('admin', 'Last editor'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'last_editor_id',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['creation_date', 'update_date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'update_date',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * Retrieves the Author of the Static Page data
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * Retrieves the Last Editor of the Static Page data
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastEditor()
    {
        return $this->hasOne(User::className(), ['id' => 'last_editor_id']);
    }


    public static function derp($models)
    {
        return
            [
                3 => ['cacafuti' => 'dato cacafuti', 'pepefutir' => 'dato pepefuti'],
                4 => ['pepefutir' => 'dato2 pepefuti']
            ];
    }

    public static function derp2($models)
    {
        return
            [
                3 => ['perraca' => 'dato perraca',],
                4 => ['perraca' => 'dato2 perraca']
            ];
    }

}
