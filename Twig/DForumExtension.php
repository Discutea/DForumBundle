<?php

namespace Discutea\DForumBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface as Poster;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Post;

class DForumExtension extends \Twig_Extension
{
    private $em;

    public function __construct (EntityManager $em) {
        $this->em = $em;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dfPostByPoster', array($this, 'dfPostByPoster')),
            new \Twig_SimpleFunction('dfTopicsByPoster', array($this, 'dfTopicsByPoster')),
            new \Twig_SimpleFunction('dfLastTopics', array($this, 'dfLastTopics')),
            new \Twig_SimpleFunction('dfLastTopicsPinned', array($this, 'dfLastTopicsPinned')),
            new \Twig_SimpleFunction('dfLastTopicsClosed', array($this, 'dfLastTopicsClosed')),
            new \Twig_SimpleFunction('dfLastTopicsResolved', array($this, 'dfLastTopicsResolved')),
            new \Twig_SimpleFunction('dfLastPosts', array($this, 'dfLastPosts')),
            new \Twig_SimpleFunction('dfLastPostsEdited', array($this, 'dfLastPostsEdited')),
            new \Twig_SimpleFunction('dfLastTopicInForum', array($this, 'dfLastTopicInForum')),
            new \Twig_SimpleFunction('dfPostsPageCount', array($this, 'dfPostsPageCount')),
        );
    }

    public function dfPostByPoster(Poster $poster, $limit = null)
    {
        $posts = $this->em->getRepository('DForumBundle:Post')->findBy(
            array('poster' => $poster),
            array('date' => 'DESC'),
            $limit,
            null);

        return $posts;
    }

    public function dfTopicsByPoster(Poster $poster, $limit = null)
    {
        $topics = $this->em->getRepository('DForumBundle:Topic')->findBy(
            array('user' => $poster),
            array('date' => 'DESC'),
            $limit,
            null);

        return $topics;
    }
    
    public function dfLastTopics($limit = null)
    {
        $topics = $this->em->getRepository('DForumBundle:Topic')->findBy(
            array(),
            array('date' => 'DESC'),
            $limit,
            null);

        return $topics;
    }

    public function dfLastTopicsClosed($limit = null)
    {
        $topics = $this->em->getRepository('DForumBundle:Topic')->findBy(
            array('closed' => true),
            array('date' => 'DESC'),
            $limit,
            null);

        return $topics;
    }

    public function dfLastTopicsPinned($limit = null)
    {
        $topics = $this->em->getRepository('DForumBundle:Topic')->findBy(
            array('pinned' => true),
            array('date' => 'DESC'),
            $limit,
            null);

        return $topics;
    }
    
    public function dfLastTopicsResolved($limit = null)
    {
        $topics = $this->em->getRepository('DForumBundle:Topic')->findBy(
            array('resolved' => true),
            array('date' => 'DESC'),
            $limit,
            null);

        return $topics;
    }
    
    public function dfLastPosts($limit = null)
    {
        $posts = $this->em->getRepository('DForumBundle:Post')->findBy(
            array(),
            array('date' => 'DESC'),
            $limit,
            null);

        return $posts;
    }
    public function dfLastPostsEdited($limit = null)
    {
       
        $posts = $this->em->getRepository('DForumBundle:Post')->findLastEdited($limit);

        return $posts;
    }
    
    public function dfLastTopicInForum(Forum $forum)
    {
        $topic = $this->em->getRepository('DForumBundle:Topic')->findOneBy(
            array('forum' => $forum, 'pinned' => null),
            array('lastPost' => 'DESC'));
        return $topic;
    }

    public function dfPostsPageCount(Post $post)
    {
    //    $topic = $post->getTopic();
    //    $posts = $this->pagin->pagignate('posts', $topic->getPosts());
    
        return 1;
    }

    public function getName()
    {
        return 'discutea.forumbundle_extension';
    }
    
}
