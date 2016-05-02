<?php

namespace Discutea\DForumBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DForumBundle\Entity\Post;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\TopicRepository")
 * @ORM\Table(name="df_topic")
 * 
 */
class Topic
{

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="string")
     */
    protected $locale;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     *
     */
    protected $pinned;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     *
     */
    protected $resolved;
    
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     *
     */
    protected $closed;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DForumBundle\Entity\Forum", inversedBy="topics")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id", nullable=false)
     */
    protected $forum;
    
    /**
     * @ORM\OneToMany(targetEntity="Discutea\DForumBundle\Entity\Post", mappedBy="topic"))
     */
    protected $posts;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastPost;
    
    /**
     * Constructor
     */
    public function __construct($locale = "en")
    {
        $this->posts = new ArrayCollection();
        $this->date = new \Datetime();    
        $this->setLocale($locale);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Topic
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set forum
     *
     * @param \Discutea\DForumBundle\Entity\Forum $forum
     *
     * @return Topic
     */
    public function setForum(Forum $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Get forum
     *
     * @return \Discutea\DForumBundle\Entity\Forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Add post
     *
     * @param \Discutea\DForumBundle\Entity\Post $post
     *
     * @return Topic
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Discutea\DForumBundle\Entity\Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set user
     *
     * @param \Discutea\UsersBundle\Entity\Users $user
     *
     * @return Topic
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Discutea\UsersBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set pinned
     *
     * @param boolean $pinned
     *
     * @return Topic
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * Get pinned
     *
     * @return boolean
     */
    public function getPinned()
    {
        return $this->pinned;
    }

    /**
     * Set resolved
     *
     * @param boolean $resolved
     *
     * @return Topic
     */
    public function setResolved($resolved)
    {
        $this->resolved = $resolved;

        return $this;
    }

    /**
     * Get resolved
     *
     * @return boolean
     */
    public function getResolved()
    {
        return $this->resolved;
    }

    /**
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Topic
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set lastPost
     *
     * @param \DateTime $lastPost
     *
     * @return Topic
     */
    public function setLastPost($lastPost)
    {
        $this->lastPost = $lastPost;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return Topic
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get lastPost
     *
     * @return \DateTime
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }
}
