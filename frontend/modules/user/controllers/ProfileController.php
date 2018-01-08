<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;

class ProfileController extends Controller
{

    public function actionView($id)
    {
        return $this->render('view', [
                    'user' => $this->findUser($id),
        ]);
    }

    private function findUser($id)
    {
        if ($user = User::find()->where(['id' => $id])->one())
        {
            return $user;
        }
        throw new NotFoundHttpException();
    }

}
