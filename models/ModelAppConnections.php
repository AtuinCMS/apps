<?php

namespace atuin\apps\models;

use Yii;
use yii\base\Model;

/**
 * Class ModelAppConnections
 *
 * Will store the App dependences in the Atuin System
 *
 * @package atuin\apps\models
 */
class ModelAppConnections extends Model
{

    /**
     * Inserts a new connection from an App into any other
     * section of Atuin. It will be used to store the App
     * dependences into the system.
     *
     * @param $event
     */
    public function insertConnectionFromFilter($event)
    {
        if (is_null($event->data))
        {
            return;
        }

        $app = $event->data;
        $sender = $event->sender;

        $newConnection = new AppConnections();

        $newConnection->app_id = $app->id;

        $newConnection->type = $sender::className();

        $newConnection->reference_id = $sender->id;

        $newConnection->save();

    }

}