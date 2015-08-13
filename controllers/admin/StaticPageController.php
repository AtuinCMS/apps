<?php
namespace atuin\apps\controllers\admin;

use atuin\apps\widgets\staticPage\models\StaticPlugin;
use atuin\apps\widgets\staticPage\models\StaticPluginSearch;
use atuin\skeleton\controllers\admin\BaseAdminController;
use Yii;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;


/**
 * Class StaticPageController
 * @package atuin\apps\controllers\admin
 */
class StaticPageController extends BaseAdminController
{

    /**
     * List of static Pages already done
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StaticPluginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    /**
     * Form to create a new Static Page
     *
     * @return string
     */
    public function actionCreate()
    {
        $staticPlugin = new StaticPlugin();

        if ($staticPlugin->load(Yii::$app->request->post()) and $staticPlugin->validate()) {
            $staticPlugin->save();

            // Redirects to the pages index
            $this->redirect('@web/pages/static');
        }

        return $this->render('create', ['staticPlugin' => $staticPlugin]);

    }

    public function actionUpdate($id)
    {
        /** @var StaticPlugin $staticPlugin */
        $staticPlugin = StaticPlugin::findOne($id);

        if ($staticPlugin->load(Yii::$app->request->post()) and $staticPlugin->validate()) {
            $staticPlugin->save();

            // Redirects to the pages index
            $this->redirect('@web/pages/static');
        }

        return $this->render('create', ['staticPlugin' => $staticPlugin]);

    }

    public function actionDelete($id)
    {

        /** @var StaticPlugin $staticPlugin */
        $staticPlugin = StaticPlugin::findOne($id);

        $staticPlugin->delete();

        // Redirects to the pages index
        $this->redirect('@web/pages/static');
    }


    public function actionView($id)
    {
        /** @var StaticPlugin $staticPlugin */
        $staticPlugin = StaticPlugin::findOne($id);

        return $this->render('view', ['staticPlugin' => $staticPlugin]);
    }

}
