<?php
/**
 * 萌股 - 二次元潮流聚集地
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>
<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pid', ['labelOptions' => ['label' => '帖子ID']])->textInput() ?>

    <?= $form->field($model, 'moego_code', ['labelOptions' => ['label' => '评论人萌股号']])->textInput() ?>

    <?= $form->field($model, 'content', ['labelOptions' => ['label' => '内容']])->textarea(['rows' => 40]) ?>

    <?= $form->field($model, 'photos[]')->widget(FileInput::classname(), [
        'options' => ['multiple' => true],
        'pluginOptions' => [
            // 需要预览的文件格式
            'previewFileType' => 'image',
            // 预览的文件
            'initialPreview' => isset($p1) ? $p1 : '',
            // 需要展示的图片设置，比如图片的宽度等
            'initialPreviewConfig' => isset($p2) ? $p2 : '',
            // 是否展示预览图
            'initialPreviewAsData' => true,

            // 最多上传的文件个数限制
            'maxFileCount' => 9,
            'browseOnZoneClick' => true,
            'fileActionSettings' => [
                'showZoom' => true,
                'showRemove' => true
            ],
        ]
    ])->label('图片<span>（最多上传9张）</span>') ?>

    <?= $form->field($model, 'parent_id', ['labelOptions' => ['label' => '父评论ID']])->textInput() ?>

    <?= $form->field($model, 'is_del', ['labelOptions' => ['label' => '状态']])->radioList(['0'=>'显示','1'=>'隐藏']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
