<?php

namespace yiiplus\appversion\modules\api\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Module` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return 'ok';
    }


    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array the behavior configurations.
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authValidate'] = [
            'class' => 'app\extensions\auth\AccessTokenAuth',
            'except' => ['*'],
        ];

        return $behaviors;
    }

    /**
     * 配置获取hosts的action
     *
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yiiplus\appDevhosts\actions\ApiAction'
            ],
        ];
    }
}
