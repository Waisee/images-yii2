<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use frontend\modules\post\models\forms\PostForm;
use frontend\models\Post;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\modules\post\models\forms\CommentForm;
use frontend\modules\post\models\Comment;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{

    public function actionCreate()
    {
        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save())
            {
                Yii::$app->session->setFlash('success', 'Post created');
                return $this->goHome();
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Renders the create view for the module
     * @return string
     */
    public function actionView($id)
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);
        $comments = Comment::find()->where(['post_id' => $post->id])->orderBy('created_at')->all();
        if ($currentUser)
        {
            $model = new CommentForm($currentUser, $post);
            if ($model->load(Yii::$app->request->post()))
            {
                $model->save();
                return $this->refresh();
            }
            return $this->render('view', [
                        'model' => $model,
                        'post' => $post,
                        'currentUser' => $currentUser,
                        'comments' => $comments,
            ]);
        } else
        {
            return $this->redirect(['/user/default/login']);
        }
    }

    public function actionLike()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->like($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $post = $this->findPost($id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $post->unLike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function findPost($id)
    {
        if ($post = Post::findOne($id))
        {
            return $post;
        }
        throw new NotFoundHttpException();
    }

    public function actionDelete($id, $post_id)
    {
        $comment = Comment::findOne($id);
        if ($comment->delete())
        {
            return $this->redirect(['/post/default/view', 'id' => $post_id]);
        }
        throw new NotFoundHttpException();
    }

    public function actionEdit($id, $post_id)
    {
        $comment = Comment::findOne($id);
        //$model = new CommentForm();
        if ($post = Yii::$app->request->post())
        {
//            echo '<pre>';
//            print_r($post['Comment']['text_comment']);
//            echo '</pre>';
//            die;
            $comment->text_comment = $post['Comment']['text_comment'];
            if ($comment->save())
            {
                return $this->redirect(['/post/default/view', 'id' => $post_id]);
            }
        }
        return $this->render('edit', [
                    'comment' => $comment,
        ]);

        throw new NotFoundHttpException();
    }

}
