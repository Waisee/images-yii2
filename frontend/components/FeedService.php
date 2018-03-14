<?php

namespace frontend\components;

use yii\base\Component;
use frontend\models\Feed;

/**
 * Feed component
 * 
 * @author waise
 */
class FeedService extends Component
{

    public function addToFeeds(\yii\base\Event $event)
    {
        $user = $event->getUser();
        
        $followers = $user->getFollowers();
        $post = $event->getPost();
        
        foreach ($followers as $follower)
        {
            $feedItem = new Feed();
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture =$user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem-> post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->save();

        }
    }
}
