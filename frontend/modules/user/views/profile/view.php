<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\user */
/* @var $currentUser frontend\models\user */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm  */
/* @var $posts frontend\models\Post  */

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use dosamigos\fileupload\FileUpload;

$this->title = Html::encode($user->username);
?>
<div class="page-posts no-padding">
    <div class="row">
        <div class="page page-post col-sm-12 col-xs-12 post-82">


            <div class="blog-posts blog-posts-large">

                <div class="row">

                    <!-- profile -->
                    <article class="profile col-sm-12 col-xs-12">                                            
                        <div class="profile-title">
                            <img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
                            <div class="author-name"><?php echo Html::encode($user->username); ?></div>
                            <?php if ($currentUser && $currentUser->equals($user)): ?>
                                <?=
                                FileUpload::widget([
                                    'model' => $modelPicture,
                                    'attribute' => 'picture',
                                    'url' => ['/user/profile/upload-picture'],
                                    'options' => ['accept' => 'image/*'],
                                    'clientEvents' => [
                                        'fileuploaddone' => 'function(e, data) {
                                        if (data.result.success) {
                                            $("#profile-image-success").show();
                                            $("#profile-image-fail").hide();
                                            $("#profile-picture").attr("src", data.result.pictureUri);
                                        } else {
                                            $("#profile-image-fail").html(data.result.errors.picture).show();
                                            $("#profile-image-success").hide();
                                        }
                                    }',
                                    ],
                                ]);
                                ?>
                                <!--                                <a href="#" class="btn btn-default">Upload profile image</a>-->
                                <a href="<?php echo Url::to(['/user/profile/delete-picture']); ?>" class="btn btn-danger">Delete image</a>
                            <?php endif ?>
                            <br><br>
                            <div class="alert alert-success display-none" id="profile-image-success">Profile image updated</div>
                            <div class="alert alert-danger display-none" id="profile-image-fail"></div>
                        </div>
                        <?php if ($currentUser && !($currentUser->equals($user))): ?>
                            <hr>
                            <?php if ($currentUser->isFollowing($user)): ?>
                                <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Unubscribe</a>
                            <?php else: ?>
                                <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-info">Subscribe</a>
                            <?php endif ?>
                            <hr>
                            <?php if ($items = $currentUser->getMutualSubscriptionsTo($user)): ?>
                                <h5>Friends, who are also following <?php echo Html::encode($user->username); ?>: </h5>
                                <div class="row">
                                    <?php foreach ($items as $item): ?>
                                        <div class="col-md-12">
                                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                                                <?php echo Html::encode($item['username']); ?>
                                            </a>
                                        </div>                
                                    <?php endforeach; ?>
                                </div><hr>
                            <?php endif ?>
                            
                        <?php endif; ?>
                        <?php if ($user->about): ?>
                            <div class="profile-description">
                                <p><?php echo HtmlPurifier::process($user->about); ?></p><hr>
                            </div>
                        <?php endif; ?>
                        
                        <div class="profile-bottom">
                            <div class="profile-post-count">
                                <span><?php echo $user->getPostCount(); ?> posts</span>
                            </div>
                            <div class="profile-followers">
                                <a href="#" data-toggle="modal" data-target="#myModal2"><?php echo $user->countFollowers() ?> followers</a>
                            </div>
                            <div class="profile-following">
                                <a href="#" data-toggle="modal" data-target="#myModal1"><?php echo $user->countSubscriptions() ?> following</a>    
                            </div>
                        </div>
                    </article>
                    <div class="col-sm-12 col-xs-12">
                        <div class="row profile-posts">
                            <?php if ($posts): ?>
                                <?php foreach ($posts as $post): ?>
                                    <div class="col-md-4 profile-post">
                                        <a href="<?php echo Url::to(['/post/default/view', 'id' => ($post->id)]); ?>">
                                            <img src="<?php echo Yii::$app->storage->getFile($post->filename); ?>" class="author-image" />
                                        </a>
                                        <?php echo Html::encode($post->description); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal subscriptions -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getSubscriptions() as $subscription): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                                <?php echo Html::encode($subscription['username']); ?>
                            </a>
                        </div>                
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal followers -->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Followers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($user->getFollowers() as $follower): ?>
                        <div class="col-md-12">
                            <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
                                <?php echo Html::encode($follower['username']); ?>
                            </a>
                        </div>                
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>