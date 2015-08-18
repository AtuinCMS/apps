<?php

namespace atuin\apps\models;


use atuin\config\models\Config;
use Yii;
use yii\base\Event;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Class App
 * @package common\engine\atuin\apps\models
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $className
 * @property string $directory
 * @property string $namespace
 * @property string $version
 * @property string $alias
 * @property string $install_date
 * @property string $status
 * @property int $backend
 * @property int $frontend
 * @property int $core_module
 */
class App extends ActiveRecord
{

    protected $_isUpdated;

    public static function tableName()
    {
        return 'apps';
    }

    /**
     * @inheritdoc
     */
    function init()
    {
        parent::init();

        // list of automatic events needed

        $this->on($this::EVENT_AFTER_INSERT, [$this, '_deleteCache']);

        $this->on($this::EVENT_AFTER_UPDATE, [$this, '_deleteCache']);

        $this->on($this::EVENT_AFTER_DELETE, [$this, '_deleteCache']);
    }


    /**
     * Deletes the cache for the frontend and backend loaded data using makeCacheTag method
     *
     * @param Event $event
     */
    public function _deleteCache(Event $event)
    {
        $frontend = [0, 1];
        $backend = [0, 1];

        foreach ($frontend as $_f) {
            foreach ($backend as $_b) {
                TagDependency::invalidate(Yii::$app->cache, self::makeCacheTag($_f, $_b));
            }
        }
    }

    public static function makeCacheTag($frontend, $backend)
    {
        return self::className() . '_frontend_' . $frontend . '_backend_' . $backend;
    }

    /**
     * Returns all the connections of the Apps in the AppConnections Active Record
     *
     * Useful to retrieve all the configs, pages and extra data that Apps have
     *
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppConnections()
    {
        return $this->hasMany(AppConnections::className(), ['app_id' => 'id']);
    }


    /**
     * Retrieves all the Configs assigned to the filtered Apps using AppConnections
     * as junction table.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfigs()
    {
        return $this->hasMany(Config::className(), ['id' => 'reference_id'])
            ->via('appConnections', function ($query) {
                $query->where(['type' => Config::className()]);
            });
    }

    public function getIsUpdated()
    {
        if (is_null($this->_isUpdated)) {
            $appMarket = ModelApp::getAppMarket();

            if (array_key_exists($this->namespace, $appMarket) && $appMarket['namespace']['version'] != $this->version) {
                $this->_isUpdated = FALSE;
            } else {
                $this->_isUpdated = TRUE;
            }
        }

        return $this->_isUpdated;

    }

}