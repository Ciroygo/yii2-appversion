<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\App */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])?>

    <?php
    $html =  <<<EOF
<a type="button" data-container="body" data-toggle="popover" data-placement="right" data-content="应用标识码，不能重复">
  <i class="fa fa-fw fa-question-circle"></i>
</a>
EOF;
    ?>
    <?= $form->field($model, 'application_id')->textInput(['maxlength' => true])->label('应用Key' . $html) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$popoverRegister = <<<JS
    $(function () {
      $('[data-toggle="popover"]').popover()
    })
JS;
$this->registerJs($popoverRegister);
?>