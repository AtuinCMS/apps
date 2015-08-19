<?php
namespace atuin\apps\controllers\admin;

use atuin\apps\models\ModelApp;
use atuin\apps\models\searchs\AppSearch;
use atuin\skeleton\controllers\admin\BaseAdminController;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;


/**
 * Class StaticPageController
 * @package atuin\apps\controllers\admin
 */
class AppsController extends BaseAdminController
{

    /**
     * List of Apps already installed in the system
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
     * Install a new App in the system with the AppId
     *
     * @param $appId
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionInstall($appId)
    {
        // Must be a post call

        if (Yii::$app->request->getIsPost() === FALSE) {
            throw new BadRequestHttpException(Yii::t('admin', "Bad call method."));
        }

        $modelApp = new ModelApp();

        // Check if App is not installed
        if (!array_key_exists($appId, $modelApp->getNotInstalledMarketApps())) {
            throw new \yii\web\NotFoundHttpException(Yii::t('admin', "Selected App doesn't exist in the market."));
        }

        // Installs the App passing the AppId
        $modelApp->installAppFromID($appId);

        return $this->goBack();
    }

    /**
     * Panel to add new Atuin Apps from the Atuin Market
     *
     * @return string
     */
    public function actionMarket()
    {
        $modelApp = new ModelApp();

        $appList = new ArrayDataProvider([
            'allModels' => $modelApp->getNotInstalledMarketApps(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);

        return $this->render('market', ['dataProvider' => $appList]);
    }


    /**
     * Updates the selected App passing its row id
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUpdate($id)
    {
        $modelApp = new ModelApp();

        return $this->goBack();
    }
}
