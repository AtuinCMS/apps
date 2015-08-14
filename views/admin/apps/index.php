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
                <?= Html::a(Yii::t('admin', 'Add new App'),
                    ['../apps/add'],
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
                            'install_date'

                        ],
                    ]
                ) ?>

                <?php \yii\widgets\Pjax::end(); ?>

            </p>
        </div>
    </div>
</div>
