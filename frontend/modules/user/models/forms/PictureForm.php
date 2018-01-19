<?php

/**
 * @author waise
 */

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;

class PictureForm extends Model
{

    public $picture;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'maxSize' => $this->getMaxFileSize(),
                'extensions' => ['jpg'],
                'checkExtensionByMimeType' => true
            ],
        ];
    }

    /**
     * @return integer
     */
    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }

}
