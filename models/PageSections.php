<?php
namespace atuin\apps\models;


use yii\db\ActiveRecord;

/**
 * Class PageSections
 * @package atuin\apps\models
 *
 * Sections that will show the page plugins in different formats (1,2,3 columns...)
 *
 * @property int $id
 * @property string $name
 * @property int $cols
 *  Cols sizes stores the size in Bootstrap column format of each column separated by commas
 *  This sizes will be used like:
 *          lg and md -> the defined
 *          sm xs -> every col will be full width
 *
 * @property string $cols_sizes
 *
 */
class PageSections extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'page_sections';
    }
}