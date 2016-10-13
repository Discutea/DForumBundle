<?php

namespace Discutea\DForumBundle\Entity\Model;

use Doctrine\ORM\Mapping as ORM;
use \Discutea\DForumBundle\Entity\Topic;
use Symfony\Component\Security\Core\User\UserInterface;
use \Datetime;

/**
 * 
 * @ORM\MappedSuperclass
 * 
 */
abstract class BasePost
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
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;  

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DForumBundle\Entity\Topic", inversedBy="posts")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    protected $topic;
    
    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     */
    protected $poster;
    
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface")
     * @ORM\JoinColumn(name="updated_by", nullable=true, referencedColumnName="id")
     */
    protected $updatedBy;
  
  
    public function __construct() {
        $this->setDate(new DateTime());
    }


    /**
     * Set id
     *
     * @param int $id
     *
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Post
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
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Post
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set topic
     *
     * @param \Discutea\DForumBundle\Entity\Topic $topic
     *
     * @return Post
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \Discutea\DForumBundle\Entity\Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set poster
     *
     * @param \Discutea\UsersBundle\Entity\Users $poster
     *
     * @return Post
     */
    public function setPoster(UserInterface $poster = NULL)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return \Discutea\UsersBundle\Entity\Users
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * Set updatedBy
     *
     * @param \Discutea\UsersBundle\Entity\Users $updatedBy
     *
     * @return Post
     */
    public function setUpdatedBy(UserInterface $updatedBy = NULL)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Discutea\UsersBundle\Entity\Users
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
