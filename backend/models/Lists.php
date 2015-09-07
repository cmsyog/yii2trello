<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lists".
 *
 * @property integer $id
 * @property string $title
 * @property integer $position
 * @property integer $board_id
 * @property integer $archived
 * @property string $created_at
 * @property string $updated_at
 */
class Lists extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lists';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'position', 'board_id'], 'required'],
            [['position', 'board_id', 'archived'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'position' => 'Position',
            'board_id' => 'Board ID',
            'archived' => 'Archived',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
