<?php
/* @var $this yii\web\View */
/* @var $post frontend\model\Post */
/* @var $currentUser frontend\models\user */
/* @var $model frontend\modules\post\models\forms\CommentForm */
/* @var $comments frontend\modules\post\models\forms\Comment */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\helpers\Url;
?>

<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- feed item -->
                    <article class="post col-sm-12 col-xs-12">                                            
                        <div class="post-meta">
                            <div class="post-title">
                                <img src="<?php echo $post->getUser()->getPicture(); ?>" class="author-image" />
                                <div class="author-name"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($post->getUser()->getNickname()) ? $post->getUser()->getNickname() : $post->getUser()->getId()]); ?>">
                                        <?php if ($post->user): ?>
                                            <?php echo $post->user->username; ?>
                                        <?php endif; ?></a></div>
                            </div>
                        </div>
                        <div class="post-type-image">
                            <a href="#">
                                <img src="<?php echo $post->getImage(); ?>" alt="">
                            </a>
                        </div>
                        <div class="post-description">
                            <p><?php echo Html::encode($post->description); ?></p>
                        </div>
                        <div class="post-bottom">
                            <div class="post-likes">
                                <i class="fa fa-lg fa-heart-o"></i>
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                &nbsp;
                                <a href="#" class="btn btn-default button-unlike <?php echo ($currentUser->likesPost($post->id)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                    Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
                                </a>
                                <a href="#" class="btn btn-default button-like <?php echo ($currentUser->likesPost($post->id)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                    Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
                                </a>
                            </div>
                            <div class="post-comments">
                                <?php if ($countComment = $post->countComments()): ?>
                                    <span class="comments-count"><?php echo $countComment; ?></span> 
                                <?php else: ?>
                                    <span class="comments-count"><?php echo 0; ?></span>
                                <?php endif; ?>  comments
                            </div>
                            <div class="post-date">
                                <span><?php echo Yii::$app->formatter->asDatetime($post->created_at); ?></span>    
                            </div>
                            <div class="post-report">
                                <a href="#" class="btn btn-default button-complain" data-id="<?php echo $post->id; ?>">
                                    Report post<i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display:none"></i>
                                </a>    
                            </div>
                        </div>
                    </article>
                    <!-- feed item -->


                    <div class="col-sm-12 col-xs-12">
                        <h4>
                            <?php if ($countComment = $post->countComments()): ?>
                                <span class="comments-count"><?php echo $countComment; ?></span> 
                            <?php else: ?>
                                <span class="comments-count"><?php echo 0; ?></span>
                            <?php endif; ?> comments
                        </h4>
                        <div class="comments-post">
                            <div class="single-item-title"></div>
                            <div class="row">
                                <ul class="comment-list">
                                    <!-- comment item -->
                                    <?php foreach ($comments as $comment): ?>
                                        <li class="comment">
                                            <div class="comment-user-image">
                                                <img src="<?php //echo $comment->user->getPicture();   ?>">
                                            </div>
                                            <div class="comment-info">
                                                <h4 class="author"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($comment->getUser()->getNickname()) ? $comment->getUser()->getNickname() : $comment->getUser()->getId()]); ?>">
                                                        <?php if ($comment->user): ?>
                                                            <?php echo $comment->user->username; ?>
                                                        <?php endif; ?></a>
                                                    <span>(<?php echo Yii::$app->formatter->asDatetime($comment->created_at); ?>)</span>
                                                </h4>
                                                <p><?php echo $comment->text_comment; ?></p>
                                                <?php if ($currentUser->id == $comment->user_id): ?>
                                                    <a href="<?php echo Url::to(['/post/default/edit-comment', 'id' => $comment->id, 'post_id' => $post->getId()]); ?>" class="btn btn-info">Edit</a>            
                                                <?php endif; ?>
                                                <?php if ($currentUser->id == $comment->post->user_id): ?>
                                                    <a href="<?php echo Url::to(['/post/default/delete-comment', 'id' => $comment->id, 'post_id' => $post->getId()]); ?>" class="btn btn-danger">X</a>               
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                    <!-- comment item -->                                                 
                                </ul>
                            </div>

                        </div>
                        <!-- comments-post -->
                    </div>                 
                    <div class="col-sm-12 col-xs-12">
                        <div class="comment-respond">
                            <h4>Leave a Reply</h4>
                            <?php $form = ActiveForm::begin(); ?>

                            <?php echo $form->field($model, 'text_comment'); ?>

                            <?php
                            echo Html::submitButton('Send', [
                                'class' => 'btn btn-secondary',
                            ]);
                            ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);

$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);
