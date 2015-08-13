<?php

namespace atuin\apps\models;
use yii\db\ActiveRecord;


/**
 * Class PagePluginData
 * @package atuin\apps\models
 *
 * @property int $id
 * @property string $className
 * @property int $reference_id
 * @property int $plugin_id
 * @property int $plugin_reference_id
 */
class PagePluginData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'page_plugin_data';
    }
}