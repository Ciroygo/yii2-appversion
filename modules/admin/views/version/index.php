<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel yiiplus\appversion\modules\admin\models\VersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '版本管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (!empty(Yii::$app->session->getFlash('success'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-info'],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
} elseif (!empty(Yii::$app->session->getFlash('error'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-error'],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
}
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title ?></h3>
        <?php Pjax::begin(); ?>
        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
            <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增',
                ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6"></div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // 'id',
                        'app_id',
                        'code',
                        'min_code',
                        'name',
                        'min_name',
                        'type',
                        'platform',
                        'scope',
                        'desc:text',
                        //'status',
                        'operated_id',
                        //'is_del',
                        //'created_at',
                        //'updated_at',
                        //'deleted_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                    'layout'=>"{items}<div class='col-sm-11'>{summary}<div class='pull-right'>{pager}</div></div>",
                    'tableOptions' => ['class' => 'table table-hover']
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
