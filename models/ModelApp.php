<?php

namespace atuin\apps\models;

use atuin\installation\app_installation\AppConfigManagement;
use atuin\installation\app_installation\AppInstaller;
use atuin\installation\app_installation\AppLoader;
use atuin\installation\app_installation\AppManagement;
use atuin\installation\app_installation\helpers\FactoryCommandHelper;
use atuin\installation\helpers\ComposerAppHandler;
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

    /**
     * Return data from the market for the assigned apps in the $appIds parameter
     *
     * @param array $appIds
     * @return array
     */
    public static function getAppMarketData($appIds)
    {
        if (!is_array($appIds))
        {
            $appIds = [$appIds];
        }

        $appList = self::getAppMarket();

        $appDataList = [];

        foreach ($appIds as $appId)
        {
            if (array_key_exists($appId, $appList))
            {
                $appDataList[$appId] = $appList[$appId];
            }
        }

        return $appDataList;
    }

    /**
     * Fetches only the not installed Apps from the market urls
     *
     * @return array
     */
    public function getNotInstalledMarketApps()
    {
        $appList = $this->getAppMarket();

        /** @var App[] $installedApps */
        $installedApps = App::find()->all();

        foreach ($installedApps as $app)
        {
            if (array_key_exists($app->app_id, $appList))
            {
                unset($appList[$app->app_id]);
            }
        }

        return $appList;
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

        if ($cacheData)
        {
            //        return $cacheData;
        }

        $urls = Yii::$app->getModule('apps')->appMarketUrls;

        if (empty($urls))
        {
            throw new InvalidParamException('App market URL list is empty!');
        }
        elseif (!is_array($urls))
        {
            $urls = [$urls];
        }

        $appList = [];

        foreach ($urls as $url)
        {

            // Get the json data with a timeout of 5 seconds
            $ctx = stream_context_create(array(
                    'http' => array(
                        'timeout' => 5
                    )
                )
            );

            $data = @file_get_contents($url, 0, $ctx);

            if ($data !== FALSE)
            {
                $data = json_decode($data, TRUE);
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

        if (array_key_exists($appId, $appList))
        {
            $data = $appList[$appId];
            $this->installAppFromData($data);
        }
        else
        {
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

        if (array_key_exists('composerPackage', $appData))
        {
            $installationHandler = $this->getInstallationHandler($appData);
        }

        // Check the install actions to be made (Only App installation, only Config installation, both...)
        if (is_null($installActions))
        {
            $installActions = [new AppManagement(), new AppConfigManagement()];
        }
        elseif (!is_array($installActions))
        {
            throw new InvalidParamException('installActions must be an array containing objects inheriting atuin\installation\app_installation\BaseManagement');
        }

        /** @var \atuin\skeleton\Module $app */
        $app = $appLoader->loadApp($appData['id'], $installationHandler);
        AppInstaller::execute($app, $installActions);
    }


    /**
     * Updates the app from the id passed via parameter
     *
     * @param int $id
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function updateApp($id)
    {
        $appLoader = new AppLoader();


        /** @var App $appData */
        $appData = App::findOne($id);

        // get the market data for the app because there is where it's stored the installation data
        $marketData = $this->getAppMarketData($appData->app_id);

        // Gets the installation handler, usually will be based in composer to update the module data
        $installationHandler = $this->getInstallationHandler([
            'id' => $appData->app_id,
            'namespace' => $appData->namespace,
            'composerPackage' => $marketData[$appData->app_id]['composerPackage']
        ]);

        /** @var \atuin\skeleton\Module $module */
        $module = $appLoader->updateApp($appData->app_id, $installationHandler);

        // Define the actions that will be made to update the App, in this case, the App 
        // database update and config update
        $installActions = [new AppManagement(), new AppConfigManagement()];

        AppInstaller::execute($module, $installActions, 'update');

    }

    /**
     * Decides which module handler type to use for CRUD the Apps
     * Right now we only support Composer handlers.
     *
     * @param array $appData
     * @return ComposerAppHandler|null
     * @throws \Exception
     */
    protected function getInstallationHandler($appData)
    {
        // 1 Check if system has to install the App via the official installation system (composer)
        // or another one (right now we only support composer installations).
        $installationHandler = NULL;

        if (FactoryCommandHelper::composer()->check())
        {
            /** @var ComposerAppHandler $installationHandler */
            $installationHandler = new ComposerAppHandler($appData);
        }
        else
        {
            throw new \Exception('Only composer installation supported right now.');
        }

        return $installationHandler;

    }


}