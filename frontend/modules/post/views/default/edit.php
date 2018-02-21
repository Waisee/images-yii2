<?php
/* @var $this yii\web\View */
/* @var $comment frontend\modules\post\models\forms\CommentForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div>
    <h3>Edit a comment</h3>

    <?php $form = ActiveForm::begin(/* ['action' =>['post/default/comment'] */); ?>

    <?php //echo $form->field($model, 'post_id')->hiddenInput(['value' => $post->id,])->label(false);  ?>

    <?php echo $form->field($comment, 'text_comment'); ?>

    <?php echo Html::submitButton('Edit'); ?>

    <?php ActiveForm::end(); ?>
</div>