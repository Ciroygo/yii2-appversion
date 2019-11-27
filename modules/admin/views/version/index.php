<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;

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


        <h3 class="box-title">搜索</h3>
        <div class="margin-bottom"></div>
        <div class="row">
            <div class="col-xs-8">
                <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
            <div class="col-xs-4">
                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                    <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增',
                        ['create', 'app_id' => $searchModel->app_id,
                            'platform' => $searchModel->platform], ['class' => 'btn btn-sm btn-success']) ?>
                </div>
            </div>
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
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        // 'id',
                        'app_id',
                        [
                            'attribute'=>'app_name',
                            'value' => function ($model) {
                                return $model->app->name ?? null;
                            }
                        ],
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
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{status-toggle} {channel/index} {update} {delete}',
                            'buttons' => [
                                'status-toggle' => function ($url, $model, $key) {
                                    if ($model->status == 1) {
                                        return Html::a('下架', $url, ['class' => 'btn btn-xs btn-success']);
                                    } else {
                                        return Html::a('上架', $url, ['class' => 'btn btn-xs btn-warning']);
                                    }
                                },
                                'channel/index' => function ($url, $model, $key) {
                                    $url = "/appversion/channel-version?version_id=$model->id";
                                    return Html::a('渠道管理', $url, ['class' => 'btn btn-xs btn-success']);
                                },
                                'update' => function ($url, $model, $key) {
                                    return Html::a('编辑', $url, ['class' => 'btn btn-xs btn-primary']);
                                },
                                'delete' => function ($url, $model, $key) {
                                    $url = "/appversion/version?VersionSearch%5Bapp_id%5D=$model->id&VersionSearch%5Bplatform%5D=2";
                                    return Html::a('删除', $url, ['class' => 'btn btn-xs btn-danger']);
                                },
                            ],
                            'header' => '操作',
                        ],
                    ],
                    'layout'=>"{items}<div class='col-sm-11'>{summary}<div class='pull-right'>{pager}</div></div>",
                    'tableOptions' => ['class' => 'table table-hover']
                ]); ?>
            </div>
        </div>
    </div>
</div>
