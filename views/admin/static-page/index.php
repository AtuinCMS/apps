<?php
/* @var $this yii\web\View */
/* @var $searchModel \atuin\apps\widgets\staticPage\models\StaticPluginSearch */
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
                <?= Html::a(Yii::t('admin', 'Create page'),
                    ['../pages/static/create'],
                    ['class' => 'btn btn-success']) ?>

                <?php \yii\widgets\Pjax::begin(); ?>

                <?= \atuin\engine\widgets\grid\GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                        'gridHookName' => 'staticPageGrid',
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'title',
                            [
                                'attribute' => 'author',
                                'label' => Yii::t('admin', 'Author'),
                                'value' => 'author.username'
                            ],
                            [
                                'attribute' => 'lastEditor',
                                'label' => Yii::t('admin', 'Last Editor'),
                                'value' => 'lastEditor.username'
                            ],
                            [
                                'format' => ['date', 'php:d/m/Y'],
                                'attribute' => 'creation_date',
                            ],
                            [
                                'format' => ['date', 'php:d/m/Y'],
                                'attribute' => 'update_date',
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'urlCreator' => function ($action, $model, $key, $index) {
                                    $url = NULL;
                                    if ($action === 'update') {
                                        $url = Yii::$app->getHomeUrl() . '/pages/static/update/' . $model->id;
                                    } elseif ($action == 'delete') {
                                        $url = Yii::$app->getHomeUrl() . '/pages/static/delete/' . $model->id;
                                    } elseif ($action == 'view') {
                                        $url = Yii::$app->getHomeUrl() . '/pages/static/view/' . $model->id;
                                    }
                                    return $url;
                                }
                            ],
                        ],
                    ]
                ) ?>

                <?php \yii\widgets\Pjax::end(); ?>

            </p>
        </div>
    </div>
</div>
