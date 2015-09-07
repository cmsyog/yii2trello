<?php

namespace backend\controllers;


use Yii;
use backend\components\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * DefaultController
 * @package backend\controllers
 */
class DefaultController extends Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

}
