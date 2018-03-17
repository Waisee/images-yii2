<?php

namespace frontend\models;

use Yii;
use frontend\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 */
class Post extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at',],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Get author of the post
     * @return User||null
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * Like current post by given user
     * @param \frontend\models\User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("post:{$user->getId()}:likes", $this->getId());
    }
    
    /**
     * Unlike current post by given user
     * @param \frontend\models\User $user
     */
    public function unLike(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("post:{$user->getId()}:likes", $this->getId());
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->getId()}:likes");
    }
    
    /**
     * Increments comment count by 1
     */
    public function commentCountIncrement()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->incr("post:{$this->getId()}:comments");
    }
    
    /**
     * Decrements comment count by 1
     */
    public function commentCountDecrement()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->decr("post:{$this->getId()}:comments");
    }
    
    /**
     * @return mixed
     */
    public function countComments()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->get("post:{$this->getId()}:comments");
    }

    /**
     * Check whether given user liked current post
     * @param \frontend\models\User $user
     * @return integer
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }
}
