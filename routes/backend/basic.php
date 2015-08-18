<?php
use cyneek\yii2\routes\components\Route;



/**
 * Apps
 */

Route::get('apps', 'apps/admin/apps');
Route::any('apps/add', 'apps/admin/apps/add');