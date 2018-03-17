<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;

class ProfileController extends Controller
{

    public function actionView($nickname)
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $modelPicture = new PictureForm();
        
        $user = $this->findUser($nickname);
        
        $limit = Yii::$app->params['profilePostLimit'];
        $posts = $user->getPosts($limit);

        return $this->render('view', [
                    'user' => $user,
                    'currentUser' => $currentUser,
                    'modelPicture' => $modelPicture,
                    'posts' => $posts,
        ]);
    }

    public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate())
        {
            $user = Yii::$app->user->identity;

            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);

            if ($user->save(false, ['picture']))
            {
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * @param string $nickname
     * @return type User
     * @throws NotFoundHttpException
     */
    private function findUser($nickname)
    {
        if ($user = User::find()->where(['nickname' => $nickname])
                        ->orWhere(['id' => $nickname])->one())
        {
            return $user;
        }
        throw new NotFoundHttpException();
    }

    public function actionSubscribe($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        if ($user->getId() != $currentUser->getId())
        {
            $currentUser->followUser($user);
        }

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        if ($user->getId() != $currentUser->getId())
        {
            $currentUser->unfollowUser($user);
        }

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    public function actionDeletePicture()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $currentUser->deletePicture();
//        if ($currentUser->deletePicture())
//        {
//            Yii::$app->session->setFlash('success', 'Picture deleted');
//        } else
//        {
//            Yii::$app->session->setFlash('danger', 'Error occured');
//        }

        return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
    }

    /**
     * 
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    private function findUserById($id)
    {
        if ($user = User::findOne($id))
        {
            return $user;
        }
        throw new NotFoundHttpException();
    }

}
