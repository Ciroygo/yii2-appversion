<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\ChannelVersion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="channel-version-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($version->app, 'name')->textInput(["disabled" => 'disabled']) ?>

    <?= $form->field($model, 'version_id')->textInput(["disabled" => 'disabled']) ?>

    <?= $form->field($model->version, 'platform')->textInput(["disabled" => 'disabled']) ?>

    <?= $form->field($model, 'channel_id')->dropdownList(\yiiplus\appversion\modules\admin\models\Channel::getChannelOptions($version->platform)); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
