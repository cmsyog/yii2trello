<?php

namespace backend\controllers;

use Yii;
use backend\models\Board;
use backend\models\Admin;
use yii\web\Controller;


class TestController extends Controller
{
    /**
     * Lists all Yadmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $board = new Board();
        $board->name = 'd';
        $board->description = 'd';
        $v = $board->save();
        var_dump($v);
    }

    public function actionList()
    {
        $board = Board::find()->all();
        var_dump($board);
    }


    public function actionOne()
    {
//        $board=Board::find()->where(['id' => 1])->all();
        $board = Board::find()->where(['id' => 1])->one();
        var_dump($board);
    }


    public function actionUsers()
    {
//        $board = Board::findOne(1);
//
//        $users= $board->getUsers()->all();
//
//        foreach($users as $user)
//        {
//            var_dump($user);
//            echo '----';
//        }
        $admin = Admin::findOne(1);

        $boards = $admin->getBoards()->all();

        foreach ($boards as $board) {
            var_dump($board);
        }
    }


    public function actionNew()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $admin = Admin::findOne(1);
            $board = new Board();
            $board->name = 'board';
            $board->description = 'd-description';
            $board->save();
            $admin->link('boards', $board);
            $v = $admin->save();
            var_dump($v);
            $transaction->commit();
        } catch (\Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $transaction->rollBack();
        }

    }


    public function actionRedis()
    {
        var_dump(Yii::$app->session->getCount());
    }

}