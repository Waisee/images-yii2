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

<div class="post-default-index">
    <div class="row">
        <div class="col-md-12">
            <?php if ($post->user): ?>
                <?php echo $post->user->username; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>" />
        </div>
        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

        <hr>

        <div class="col-md-12">
            Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>

            <a href="#" class="btn btn-primary button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
            </a>
            <a href="#" class="btn btn-primary button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
            </a>

        </div>
    </div>
    <div>
        <hr>
        <?php foreach ($comments as $comment): ?>
            <h3><?php if ($comment->user): ?>
                    <?php echo $comment->user->username; ?>
                <?php endif; ?><br></h3>
            <?php echo $comment->text_comment; ?>
            <?php if ($currentUser->id == $comment->user_id): ?>

                <a href="<?php echo Url::to(['/post/default/edit', 'id' => $comment->id, 'post_id' => $post->getId()]); ?>" class="btn btn-info">Edit</a>            
            <?php endif; ?>
            <?php if ($currentUser->id == $comment->post->user_id): ?>
                <a href="<?php echo Url::to(['/post/default/delete', 'id' => $comment->id, 'post_id' => $post->getId()]); ?>" class="btn btn-danger">X</a>               
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    </div>
    <div>
        <h3>Write a comment</h3>

        <?php $form = ActiveForm::begin(/* ['action' =>['post/default/comment'] */); ?>

        <?php //echo $form->field($model, 'post_id')->hiddenInput(['value' => $post->id,])->label(false);  ?>

        <?php echo $form->field($model, 'text_comment'); ?>

        <?php echo Html::submitButton('Write'); ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);
