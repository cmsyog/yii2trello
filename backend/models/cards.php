<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cards".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $due_date
 * @property integer $position
 * @property integer $list_id
 * @property integer $assignee_id
 * @property integer $archived
 * @property string $color
 * @property string $created_at
 * @property string $updated_at
 */
class Cards extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'position', 'list_id'], 'required'],
            [['title', 'description'], 'string'],
            [['due_date', 'created_at', 'updated_at'], 'safe'],
            [['position', 'list_id', 'assignee_id', 'archived'], 'integer'],
            [['color'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'due_date' => 'Due Date',
            'position' => 'Position',
            'list_id' => 'List ID',
            'assignee_id' => 'Assignee ID',
            'archived' => 'Archived',
            'color' => 'Color',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
