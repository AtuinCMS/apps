<?php
namespace atuin\apps\controllers;

use atuin\skeleton\controllers\admin\BaseAdminController;
use Yii;
use yii\web\Controller;


/**
 * Class InstallationController
 * @package atuin\engine\controllers\backend
 */
class SiteController extends BaseAdminController
{

    public function actionIndex()
    {
       return $this->render('index');
    }


}
