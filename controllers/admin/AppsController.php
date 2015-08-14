<?php
namespace atuin\apps\controllers\admin;

use atuin\apps\models\searchs\AppSearch;
use atuin\skeleton\controllers\admin\BaseAdminController;
use Yii;


/**
 * Class StaticPageController
 * @package atuin\apps\controllers\admin
 */
class AppsController extends BaseAdminController
{

    /**
     * List of Apps already installed
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }
    
    /**
     * Panel to add new Atuin Apps
     * 
     * @return string
     */
    
    public function actionAdd()
    {
        return $this->render('add_app');
    }
    
}
