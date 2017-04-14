<?php

namespace Discutea\DForumBundle\Twig;

use Symfony\Component\Security\Core\User\UserInterface as Poster;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;

class DForumExtension extends \Twig_Extension
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     *
     * @var object Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    /*
     * @var array configuration for pagination
     */
    private $config;

    public function __construct (ObjectManager $objectManager, Router $router, $paginationConfig) {
        $this->em = $objectManager;
        $this->router = $router;
        $this->config = $paginationConfig;
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
            new \Twig_SimpleFunction('dfLastPostPages', array($this, 'dfLastPostPages')),
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
            array('forum' => $forum, 'pinned' => false),
            array('lastPost' => 'DESC'));
        return $topic;
    }

    public function dfLastPostPages(Post $post)
    {   
        $paginatorEnabled = $this->config['posts']['enabled'];
        
        $topic = $post->getTopic();
        
        if ($paginatorEnabled === true) {
            $postsCount = $topic->getPosts()->count();
            $postsPerPage = $this->config['posts']['per_page'];
            $query = $this->config['page_name'];
            $pagesCount =  ceil( $postsCount / $postsPerPage );
            
            if ($pagesCount > 1) {
                return $this->router->generate('discutea_forum_post', array(
                    'slug'     => $topic->getSlug(),
                    $query => $pagesCount
                ));
            }
        }
        
        return $this->router->generate('discutea_forum_post', array('slug' => $topic->getSlug()));
    }

    public function getName()
    {
        return 'discutea.forumbundle_extension';
    }
    
}
