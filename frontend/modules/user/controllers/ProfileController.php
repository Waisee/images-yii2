<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;

class ProfileController extends Controller
{

    public function actionView($nickname)
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        
        $modelPicture = new PictureForm(); 

        return $this->render('view', [
                    'user' => $this->findUser($nickname),
                    'currentUser' => $currentUser,
                    'modelPicture' => $modelPicture,
        ]);
    }
    
    public function actionUploadPicture()
    {
        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');
        
        if ($model->validate())
        {
            $user = Yii::$app->user->identity;
            
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);
            
            if($user->save(false, ['picture']))
            {
                print_r($user->attributes);die;
            }

        }
             
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

//    private function checkUserSubscribeYourself($id)
//    {
//        $currentUser = Yii::$app->user->identity;
//
//        $user = $this->findUserById($id);
//        
//        if ($user->getId() != $currentUser->getId()){
//            return true;
//        }
//        return false;
//        
//    }
//    public function actionGenerate()
//    {
//        $faker = \Faker\Factory::create();
//        
//        for ($i = 0; $i <10; $i++)
//        {
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time,
//            ]);
//            $user->save(false);
//        }
//    }
}
