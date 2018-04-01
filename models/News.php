<?php

namespace app\models;

use dektrium\user\models\User;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $text
 * @property string $date
 * @property int $is_active
 * @property int $created_user_id
 * @property string $created_at
 * @property int $updated_user_id
 * @property string $updated_at
 *
 * @property User $createdUser
 * @property User $updatedUser
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'text', 'date', 'created_user_id', 'created_at'], 'required'],
            [['text'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['created_user_id', 'updated_user_id'], 'integer'],
            [['name', 'description', 'image'], 'string', 'max' => 255],
            [['is_active'], 'string', 'max' => 4],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_user_id' => 'id']],
            [['updated_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'image' => 'Изображение',
            'text' => 'Полный текст',
            'date' => 'Дата',
            'is_active' => 'Активна',
            'created_user_id' => 'Создавший пользователь',
            'created_at' => 'Дата создания',
            'updated_user_id' => 'Изменивший пользователь',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullText() {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getImageSrc() {
        return $this->image;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_user_id']);
    }
}
