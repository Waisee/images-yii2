<?php

namespace frontend\modules\post\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use frontend\models\User;
use frontend\models\Post;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string $text_comment
 * @property int $created_at
 * @property int $updated_at
 */
class Comment extends ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'text_comment' => 'Text Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get author of the comment
     * @return User||null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * Get post where comment was written
     * @return Post||null
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id'])->one();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $post = $this->getPost();
        $post->commentCountIncrement();
    }

}
