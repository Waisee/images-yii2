<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

/**
 * @author waise
 */
class PostForm extends Model
{

    const MAX_DESCRIPTION_LENGHT = 1000;

    public $picture;
    public $description;
    
    private $user;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxSizeFile()],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGHT],
        ];
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * 
     * @return boolean
     */

    public function save()
    {
        if ($this->validate())
        {
            $post = new Post();
            $post->description = $this->description;
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id = $this->user->getId();
            return $post->save(false);
        }
    }
    
    /**
     * Maximum size of the uploaded file
     * @return integer
     */
    private function getMaxSizeFile()
    {
        return Yii::$app->params['maxFileSize'];
    }

}
