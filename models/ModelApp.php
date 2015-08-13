<?php

namespace atuin\apps\models;

use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\caching\TagDependency;


class ModelApp extends Model
{
    /**
     *
     * @param int $frontend
     * @param int $backend
     * @return App[]
     * @throws \Exception
     */
    public static function getActiveApps($frontend = 0, $backend = 0)
    {
        return App::getDb()->cache(function ($db) use ($frontend, $backend)
        {

            $array_query = ['status' => 'active'];

            if ($backend)
            {
                $array_query['backend'] = 1;
            }

            if ($frontend)
            {
                $array_query['frontend'] = 1;
            }

            return App::find()->where($array_query)->all();
        },
            86400,
            new TagDependency([
                'tags' => App::makeCacheTag($frontend, $backend),
            ]));
    }
}