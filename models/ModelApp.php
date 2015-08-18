<?php

namespace atuin\apps\models;

use atuin\installation\app_installation\AppConfigManagement;
use atuin\installation\app_installation\AppInstaller;
use atuin\installation\app_installation\AppLoader;
use atuin\installation\app_installation\AppManagement;
use atuin\installation\app_installation\helpers\FactoryCommandHelper;
use atuin\installation\helpers\ComposerAppHandler;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Filesystem;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\caching\TagDependency;

class ModelApp extends Model
{
    /**
     * Gets the active apps installed in the system
     *
     * @param int $frontend
     * @param int $backend
     * @return App[]
     * @throws \Exception
     */
    public static function getActiveApps($frontend = 0, $backend = 0)
    {
        return App::getDb()->cache(function ($db) use ($frontend, $backend) {

            $array_query = ['status' => 'active'];

            if ($backend) {
                $array_query['backend'] = 1;
            }

            if ($frontend) {
                $array_query['frontend'] = 1;
            }

            return App::find()->where($array_query)->all();
        },
            86400,
            new TagDependency([
                'tags' => App::makeCacheTag($frontend, $backend),
            ]));
    }


    /**
     * Fetches all the apps listed in the URLs provided by que Apps system
     * as market-available Module Apps for Atuin.
     *
     * @return array
     */
    public static function getAppMarket()
    {
        $cacheData = Yii::$app->getCache()->get('appMarketUrls');

        if ($cacheData) {
            return $cacheData;
        }

        $urls = Yii::$app->getModule('apps')->appMarketUrls;

        if (empty($urls)) {
            throw new InvalidParamException('App market URL list is empty!');
        } elseif (!is_array($urls)) {
            $urls = [$urls];
        }

        $appList = [];

        foreach ($urls as $url) {
            $routeInfo = pathinfo($url);

            $urlSystem = new Filesystem(new Adapter($routeInfo['dirname']));
            $data = json_decode($urlSystem->read($routeInfo['basename']), TRUE);

            if (is_array($data)) {
                $appList = Yii\helpers\ArrayHelper::merge($appList, $data);
            }
        }

        Yii::$app->getCache()->set('appMarketUrls', $appList, 7200);

        return $appList;
    }

    /**
     * Installs a new App giving only it's ID, system will search in the Market Lists,
     * get the required data and call installAppFromData
     *
     * @param $appId
     * @throws \Exception
     */
    public function installAppFromID($appId)
    {
        $appList = $this->getAppMarket();

        if (array_key_exists($appId, $appList)) {
            $data = $appList[$appId];
            $this->installAppFromData($data);
        } else {
            throw new \Exception('The app ' . $appId . ' it\'s not defined in the Market App List');
        }

    }


    /**
     * Installs a new App from the data passed by the param $appData
     *
     * Param InstallActions must be an array containing only objects that
     * inherit from atuin\installation\app_installation\BaseManagement
     *
     *
     * @param $appData
     * @param array $installActions
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function installAppFromData($appData, $installActions = NULL)
    {
        $appLoader = new AppLoader();

        // 1 Check if system has to install the App via the official installation system (composer)
        // or another one (right now we only support composer installations).
        $installationHandler = NULL;

        if (FactoryCommandHelper::composer()->check()) {
            /** @var ComposerAppHandler $installationHandler */
            $installationHandler = new ComposerAppHandler($appData);
        } else {
            throw new \Exception();
        }

        // Check the install actions to be made (Only App installation, only Config installation, both...)
        if (is_null($installActions)) {
            $installActions = [new AppManagement(), new AppConfigManagement()];
        } elseif (!is_array($installActions)) {
            throw new InvalidParamException('installActions must be an array containing objects inheriting atuin\installation\app_installation\BaseManagement');
        }

        /** @var \atuin\skeleton\Module $app */
        $app = $appLoader->loadApp($appData['id'], $installationHandler);
        AppInstaller::execute($app, $installActions);
    }


}