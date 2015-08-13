<?php

namespace atuin\apps\models;
use yii\db\ActiveRecord;


/**
 * Class PageDesign
 * @package atuin\apps\models
 *
 * @property int $id
 *  This id will be related to PageReference id
 * @property int $page_reference_id
 * @property int $section_id
 *
 * This field will be a json string with the form
 *
 *          json_encode([
 *                          [plugin_id_1],
 *                          [plugin_id_2]
 *                      ])
 *
 * @property string $plugins
 *
 */
class PageDesign extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'page_plugin_design';
    }
}