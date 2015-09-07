<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Table;
use backend\components\db\ActiveRecord;
use backend\components\Helper;
use yii\db\ActiveQuery;



class Admin extends ActiveRecord
{
    public $message;


    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sys_admin}}';
    }



    /**
     * Returns all linked AuditMail instances
     * @return ActiveQuery
     */
    public function getBoards()
    {
        return $this->hasMany(Board::className(),['id' => 'board_id']) ->viaTable('board_members', ['member_id' => 'id']);
    }

}