<?php
use cyneek\yii2\routes\components\Route;

Route::pattern('id', '\d+');

/**
 * Checks if Static Object Exists
 */
Route::filter('checkStaticPage', function () {

    /** @var StaticPlugin $staticPlugin */
    $staticPlugin = \atuin\engine\widgets\staticPage\models\StaticPlugin::findOne(Route::input('id'));

    if (is_null($staticPlugin)) {
        throw new \yii\web\NotFoundHttpException(Yii::t('admin', "Static page doesn't exist."));
    }
    return TRUE;

});

/**
 * Static Pages
 */

Route::any('pages/static', 'apps/admin/static-page');
Route::any('pages/static/create', 'apps/admin/static-page/create');
Route::any('pages/static/update/{id}', 'apps/admin/static-page/update', ['before' => ['before' => 'checkStaticPage']]);
Route::post('pages/static/delete/{id}', 'apps/admin/static-page/delete', ['before' => ['before' => 'checkStaticPage']]);
Route::get('pages/static/view/{id}', 'apps/admin/static-page/view', ['before' => ['before' => 'checkStaticPage']]);


/**
 * Apps
 */

Route::get('apps', 'apps/admin/apps');
Route::any('apps/add', 'apps/admin/apps/add');