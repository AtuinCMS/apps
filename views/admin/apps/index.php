<?php
/* @var $this yii\web\View */
/* @var $searchModel \atuin\apps\models\searchs\AppSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */
use yii\helpers\Html;

$this->title = Yii::t('admin', 'Pages');
?>

<div class="content-header">
    <h2><?= \yii\helpers\Html::encode($this->title) ?></h2>
</div>
<div class="content body">
    <div class="box box-default">
        <div class="box-body">
            <p>
                <?= Html::a(Yii::t('admin', 'Add new App'),
                    ['../apps/market'],
                    ['class' => 'btn btn-success']) ?>

                <?php \yii\widgets\Pjax::begin(); ?>

                <?= \atuin\engine\widgets\grid\GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                        'gridHookName' => 'appsPageGrid',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name',
                            'version',
                            [
                                'format' => ['date', 'php:d/m/Y'],
                                'attribute' => 'install_date',
                            ],
                            'description',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                            Yii::$app->getHomeUrl() . '/apps/view/' . $key, [
                                                'title' => Yii::t('yii', 'View')]);
                                    },
                                    'update' => function ($url, $model, $key) {
                                        if ($model->isUpdated == FALSE) {
                                            return Html::a('<span class="glyphicon glyphicon-refresh"></span>',
                                                Yii::$app->getHomeUrl() . '/apps/update/' . $key, [
                                                    'title' => Yii::t('yii', 'Update')]);
                                        }

                                    },
                                    'delete' => function ($url, $model, $key) {
                                        if ($model->core_module == 0) {
                                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                                Yii::$app->getHomeUrl() . '/apps/delete/' . $key, [
                                                    'title' => Yii::t('yii', 'Delete'),
                                                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                                    'data-method' => 'post',
                                                ]);
                                        }
                                    }
                                ]
                            ]


                        ],
                    ]
                ) ?>

                <?php \yii\widgets\Pjax::end(); ?>

            </p>
        </div>
    </div>
</div>
