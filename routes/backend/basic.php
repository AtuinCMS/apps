<?php
use cyneek\yii2\routes\components\Route;


Route::pattern('id', '\d+');

/**
 * Checks if Static Object Exists
 */
Route::filter('checkAppId', function () {

    /** @var \atuin\apps\models\App $app */
    $app = \atuin\apps\models\App::findOne(Route::input('id'));


    if (is_null($app)) {
        throw new \yii\web\NotFoundHttpException(Yii::t('admin', "App doesn't exist."));
    }
    return TRUE;

});

/**
 * Checks if the App Market Id exists in the market
 */
Route::filter('checkAppMarketId', function () {

    /** @var array $app */
    $app = \atuin\apps\models\ModelApp::getAppMarketData(Route::input('AppMarketId'));


    if (empty($app)) {
        throw new \yii\web\NotFoundHttpException(Yii::t('admin', "App is not located in the market."));
    }
    return TRUE;

});


/**
 * Apps
 */

Route::get('apps', 'apps/admin/apps');
Route::any('apps/market', 'apps/admin/apps/market');
Route::post('apps/install/{AppMarketId}', 'apps/admin/apps/install', ['before' => ['before' => 'checkAppMarketId']]);
Route::post('apps/update/{id}', 'apps/admin/apps/update', ['before' => ['before' => 'checkAppId']]);
