<?php

namespace atuin\apps\models;

use yii\db\ActiveRecord;

/**
 * Class Plugin
 * @package atuin\apps\models
 *
 * @property int $id
 * @property string $className
 * @property string $directory
 * @property string $namespace
 * @property boolean $private
 */
class Plugin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    static function tableName()
    {
        return 'plugins';
    }

}
