<?php
namespace backend\controllers;

use backend\models\BoardActivities;
use app\models\Lists;
use backend\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\models\Board;
use yii\log\Logger;
use yii\log\FileTarget;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class BoardController extends Controller
{
    public function beforeAction()
    {

        return true;
        //todo 验证用户是否有权限访问
        $board_id = Yii::$app->request->get('id');
        $user_id = Yii::$app->user->identity->id;
        $user = User::findOne($user_id);
        $check =  $user -> getBoards()->where(['id'=>$board_id])->one();
        //var_dump($check);
        if(is_null($check)){
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }else{
            return true;
        }
    }

    public function  actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $method = Yii::$app->request->getMethod();
        $board_id = Yii::$app->request->get('id');

        return $this->render('index', ['id' => $board_id]);

    }

    /*
     *  获取cards
     */
    public function actionModel()
    {
        //todo 获取board对应的list
        $board = Board::findOne( $board_id = Yii::$app->request->post()['b']);
        if(empty($board)){
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'lists' => []
            );
        }else{
            $cardList= $board ->getCardLists()->all();
            $list=array();
            if(is_array($cardList)&&!empty($cardList)){
                foreach($cardList as $v){
                    $card['title'] = $v["title"];
                    $card['id'] = $v["id"];
                    $card['position'] = $v["position"];
                    $card['cards'] = array();
                    $card['archived'] = $v["archived"];
                    $card['created_at'] = $v["created_at"];
                    $card['updated_at'] = $v["updated_at"];
                    $list[]=$card;
                }
            }

            // $list=json_decode('[{"title":"fasd","id":1,"position":0},{"title":"fdsaf","id":2,"position":2},{"title":"fdsafd","id":3,"position":1}]');
            // $array=json_decode('[{"id":4,"title":"fds","position":1,"archived":null,"created_at":"2015-08-03 01:34:11","updated_at":"2015-08-03 01:34:11","cards":[]},{"id":5,"title":"fds","position":2,"archived":null,"created_at":"2015-08-03 01:34:13","updated_at":"2015-08-03 01:34:13","cards":[]}]');

            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'lists' => $list
            );
        }

    }

    /*
     * 增加 cardlist
     */
    public function actionList()
    {
        //todo 获取board对应的list

        //获取参数
        $date = date("Y-m-d H:i:s");
        $user_id = Yii::$app->user->identity->id;
        $board_id = Yii::$app->request->post('b');

        //定义，赋值
        $lists = new Lists();
        $lists->board_id = $board_id;
        $lists->title = Yii::$app->request->post('t');
        $lists->position = 0;
        $lists->created_at = $date;
        $lists->updated_at = $date;

        //开启事务
         $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {

            $board_activities = new BoardActivities();
            $board_activities->description='add list';
            $board_activities->board_id=$board_id;
            $board_activities->member_id=$user_id;
            $board_activities->created_at = $date;
            $board_activities->updated_at = $date;

            if ($lists->validate() && $lists->save()) {
                $board = Board::findOne($board_id);
                $board->link('boardActivities', $board_activities);
                $board->save();
               $transaction->commit();
                Yii::getLogger()->log('add a list', Logger::LEVEL_INFO, 'admin');
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = array(
                    'id' => $lists->id
                );
            } else {

            }
        } catch (\Exception $e) // 如果有一条查询失败，则会抛出异常
       {
           $transaction->rollBack();
       }

    }

    public function actionPosition(){

        //定义，赋值
        $model = new BoardActivities();
        $model -> position = Yii::$app->request->post('np');

        if ($model->validate()) {
            $model->save();
        }

    }




}