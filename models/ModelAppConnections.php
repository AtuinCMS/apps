<?php

namespace atuin\apps\models;

use Yii;
use yii\base\Model;


class ModelAppConnections extends Model
{


    public function insertConnectionFromFilter($event)
    {
        if (is_null($event->data)) {
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