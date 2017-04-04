<?php
namespace app\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;

class BehaviorsController extends Controller{

    public function actions()
    {
        $this->layout = "wpLayout";
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function behaviors() {
        return [
            'access' =>[
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['login', 'index', 'register'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => false,
                        'controllers' => ['site'],
                        'actions' => ['login'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['manage'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => false,
                        'controllers' => ['manage'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@'],
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                    
                ]
            ]
        ];
    }
}
