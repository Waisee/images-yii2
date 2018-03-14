<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
        {
            return $this->redirect(['/user/default/login']);
        }
        
        /*
         * @var @currentUser User
         */
        $currentUser = Yii::$app->user->identity;
        $limit = Yii::$app->params['feedPostLimit'];

        $feedItems = $currentUser->getFeed($limit);

        return $this->render('index', [
        
            'feedItems' => $feedItems,
            'currentUser' => $currentUser,
        ]);
    }

}
