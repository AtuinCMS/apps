<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var \atuin\engine\widgets\staticPage\models\StaticPlugin $staticPlugin */
?>


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($staticPlugin, 'title')->textInput(['maxlength' => 255]) ?>

<?= $form->field($staticPlugin, 'url')->widget(\cyneek\yii2\widget\urlparser\UrlParser::className(),
    ['maxlength' => 50, 'source' => ['model' => $staticPlugin, 'attribute' => 'title']]);
?>

<?= $form->field($staticPlugin, 'text')->widget(\dosamigos\tinymce\TinyMce::className(), ['options' => ['rows' => 6],
    'language' => 'es',
    'clientOptions' => ['plugins' => ["advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste", "image", "imagetools"],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link imagetools"]]);
?>

<div class="form-group">
    <?= Html::submitButton($staticPlugin->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $staticPlugin->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
