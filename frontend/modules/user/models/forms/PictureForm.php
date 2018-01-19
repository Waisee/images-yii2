<?php

/**
 * @author waise
 */

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use Intervention\Image\ImageManager;

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

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
    }

    /**
     * Resize image if needed+     */
    public function resizePicture()
    {
        if ($this->picture->error)
        {
            /* В объекте UploadedFile есть свойство error. Если в нем "1", значит
             * произошла ошибка и работать с изображением не нужно, прерываем
             * выполнение метода */
            return;
        }

        $width = Yii::$app->params['profilePicture']['maxWidth'];
        $height = Yii::$app->params['profilePicture']['maxHeight'];

        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($this->picture->tempName);

        // 3-й аргумент - органичения - специальные настройки при изменении размера
        $image->resize($width, $height, function ($constraint)
        {

            // Пропорции изображений оставлять такими же (например, для избежания широких или вытянутых лиц)
            $constraint->aspectRatio();

            // Изображения, размером меньше заданных $width, $height не будут изменены: 
            $constraint->upsize();
        })->save();
    }

    /**
     * @return integer
     */
    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }

}
