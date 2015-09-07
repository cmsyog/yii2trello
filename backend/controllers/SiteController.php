<?php
namespace backend\controllers;

use backend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\Board;
use yii\log\Logger;
use yii\log\FileTarget;
use yii\web\BadRequestHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $user_id = Yii::$app->user->identity->id;
        $userModel = new User();
        $boardModel = new Board();
        $user = $userModel::findOne($user_id);
        $bo_list = $user->getBoards()->all();
        foreach ($bo_list as $v) {
            $blist[] = $v->attributes;
        }
        //参数传递
        $view = Yii::$app->view;
        $view->params['blist'] = $blist;
        return $this->render('index', ['model' => $boardModel, 'uid' => $user_id
        ]);
    }

    public function actionAdd()
    {

        //$csrfToken = Yii::$app->request->csrfToken;

        $_csrf = Yii::$app->request->getBodyParam(Yii::$app->request->csrfParam);

        $ticket = Yii::$app->getSession()->get($_csrf);

       // Yii::$app->request->getCsrfToken();

        if ($ticket == 1) {
            Yii::$app->getSession()->set($_csrf, 0);
            $user_id = Yii::$app->user->identity->id;
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();

//        echo $_POST[_csrf];
//        echo Yii::$app->request->csrfToken;
//        exit;

            //数据提交过来之后执行验证
//        if(Yii::$app->request->csrfToken!=$_POST[_csrf]){
//          return $this -> render('error', ['message'=>'请勿点击过快！csrf攻击！']);
//            //Yii::$app->session->setFlash('error', 'csrf攻击！');
//        }


            try {
                $data = date("Y-m-d H:i:s");
                $user = User::findOne($user_id);
                $info = $user->attributes;
                $board = new Board();
                if ($board->load(Yii::$app->request->post()) && $board->validate() && $board->save()) {
                    $user->link('boards', $board, array('created_at' => $data, 'updated_at' => $data, 'admin' => $info['admin']));
                    $v = $user->save();
                    $transaction->commit();
                    Yii::getLogger()->log('添加一条新信息', Logger::LEVEL_INFO, 'admin');
                    Yii::$app->session->setFlash('success', Yii::t('audit', 'Create a new Board Successful'));
                } else {
                    return $this->render('index', ['model' => $board]);
                }
            } catch (\Exception $e) // 如果有一条查询失败，则会抛出异常
            {
                Yii::$app->session->setFlash('error', Yii::t('audit', 'Unknown Error'));
                $transaction->rollBack();
            }

            $this->redirect('index');
        } else {
            Yii::$app->session->setFlash('error', Yii::t('audit', 'Duplicated Submit'));
            $this->redirect('index');
        }


    }

    public function actionValidate()
    {
        $model = new Board();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


}
