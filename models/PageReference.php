<?php

namespace atuin\apps\models;
use yii\db\ActiveRecord;

/**
 * Class PageReference
 * @package atuin\apps\models
 *
 * This table will hold the specifics to link the page designs with the
 * pages per se. We are using a junction table because probably we will
 * need different page designs for the same page depending from the data
 * it's loaded in them
 *
 * @property int $id
 * @property int $page_id
 * @property string $className
 * @property int $reference_id
 *
 */
class PageReference extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'page_references';
    }
}