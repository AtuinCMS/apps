<?php

namespace atuin\apps\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * Class AppConnections
 * @package common\engine\atuin\apps\models
 *
 * @property int $id
 * @property string $type
 * @property int $app_id
 * @property int $reference_id
 */
class AppConnections extends ActiveRecord
{
    
    
    public static function tableName()
    {
        return 'app_connections';
    }

}