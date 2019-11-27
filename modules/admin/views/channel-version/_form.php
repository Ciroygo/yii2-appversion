<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

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

    <?php
        if ($version->platform == \yiiplus\appversion\modules\admin\models\App::IOS) {
            echo $form->field($model, 'url')->textInput(['maxlength' => true]);
        } else {
            echo $form->field($model, 'url')->widget(FileInput::classname());
        }
    ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
