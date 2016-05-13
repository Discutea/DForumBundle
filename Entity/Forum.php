<?php

namespace Discutea\DForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DForumBundle\Entity\Category;
use \Discutea\DForumBundle\Entity\Topic;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\ForumRepository")
 * @ORM\Table(name="df_forum")
 */
class Forum
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="disp_position", type="integer")
     */
    protected $position = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Discutea\DForumBundle\Entity\Category", inversedBy="forums")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    protected $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Discutea\DForumBundle\Entity\Topic", mappedBy="forum", cascade={"persist", "remove"}))
     * @ORM\OrderBy({"pinned" = "desc", "lastPost" = "desc"})
     */
    protected $topics;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new ArrayCollection();
        $this->readAuthorisedRoles = array();
    }

    public function __call($method, $arguments)
    {
        return \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
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
     * Set category
     *
     * @param \Discutea\DForumBundle\Entity\Category $category
     *
     * @return Forum
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Discutea\DForumBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add topic
     *
     * @param \Discutea\DForumBundle\Entity\Topic $topic
     *
     * @return Forum
     */
    public function addTopic(Topic $topic)
    {
        $this->topics[] = $topic;

        return $this;
    }

    /**
     * Remove topic
     *
     * @param \Discutea\DForumBundle\Entity\Topic $topic
     */
    public function removeTopic(Topic $topic)
    {
        $this->topics->removeElement($topic);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Get topics by locale
     *
     * @param array $locales
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTopicsByLocale($locales)
    {
        if (false === is_array($locales)) {
            $locales = array($locales => $locales);
        }
        
        $topics = $this->getTopics()->filter(
            function(Topic $entry) use ($locales) {
                return in_array($entry->getLocale(), $locales);
            }
        ); 

        return $topics;
    }
        

        
    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Forum
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set url
     *
     * @param string $image
     *
     * @return this
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     */
    public function getImage()
    {
        return $this->image;
    }
}
