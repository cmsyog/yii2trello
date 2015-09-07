<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "board_activities".
 *
 * @property integer $id
 * @property string $description
 * @property integer $board_id
 * @property integer $member_id
 * @property string $created_at
 * @property string $updated_at
 */
class BoardActivities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'board_activities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'board_id', 'member_id'], 'required'],
            [['board_id', 'member_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'board_id' => 'Board ID',
            'member_id' => 'Member ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
