<?php

namespace Discutea\DForumBundle\Controller\Base;

use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Form\Type\TopicType;
use Discutea\DForumBundle\Entity\Topic;
use Discutea\DForumBundle\Entity\Post;

/**
 * BaseTopicController 
 * 
 * This class contains useful methods for the proper functioning of the topic controller and not method actions.
 * This class extends BaseController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BaseTopicController extends BaseController
{
    /**
     * @var object $post Discutea\DForumBundle\Entity\Topic
     */
    protected $topic;

    /**
     * Generate topic form or return Null if not authorised
     *
     * @param Forum $forum
     * @return null|\Symfony\Component\Form\FormInterface
     */
    protected function generateTopicForm(Forum $forum) {
        
        if ($this->getAuthorization()->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();       
            $this->topic = new Topic();
            $this->topic->setForum($forum);
            $this->topic->setUser( $user );
            return $this->createForm(TopicType::class, $this->topic);
        }
        
        return NULL;
    }

    /**
     * Create a new post objet set content, topic and user
     *
     * @param $content
     * @param Topic $topic
     * @return Post
     */
    protected function createPost($content, Topic $topic) {
        $post = new post();
        $post->setContent($content);
        $post->setTopic($topic);
        $post->setPoster($topic->getUser());
        
        return $post;
    }
    
}
