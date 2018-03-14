<?php

namespace frontend\modules\post\models\forms;

/**
 * @author waise
 */
use yii\base\Model;
use frontend\models\User;
use frontend\models\Post;
use frontend\modules\post\models\Comment;

class CommentForm extends Model
{

    const MAX_COMMENT_LENGHT = 1000;
    
    public $post_id;
    public $text_comment;
    
    private $user;
    private $post;
    

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function rules()
    {
        return [
            [['text_comment'], 'string', 'max' => self::MAX_COMMENT_LENGHT],
        ];
    }
     
    public function save()
    {
        if ($this->validate())
        {
            $comment = new Comment();
            $comment->text_comment = $this->text_comment;
            $comment->user_id = $this->user->getId();
            $comment->post_id = $this->post->getId();
            return $comment->save(false);
        }
    }

}
