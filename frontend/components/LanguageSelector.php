<?php

namespace frontend\components;

use yii\base\BootstrapInterface;

class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguage = ['en-US', 'ru-RU'];
    
    public function bootstrap($app)
    {
        $cookiesLanguage = $app->request->cookies['language'];
        if (isset($cookiesLanguage) && in_array($cookiesLanguage, $this->supportedLanguage)){
           $app->language = $app->request->cookies['language']; 
        }
    }
}
