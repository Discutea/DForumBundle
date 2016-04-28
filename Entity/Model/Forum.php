<?php

namespace Discutea\DForumBundle\Entity\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use \Doctrine\Common\Collections\ArrayCollection;
use \Discutea\DForumBundle\Entity\Model\Category;
use \Discutea\DForumBundle\Entity\Model\Topic;

/**
 * 
 * @ORM\MappedSuperclass
 * 
 */
abstract class Forum
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", length=80, type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "4",
     *      max = "80",
     *      minMessage = "Le nom du forum doit contenir au moins {{ limit }} caractères",
     *      maxMessage = "Le nom du forum ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    protected $name;
    
    /**
     * @var text
     * 
     * @ORM\Column(name="description", length=150, type="string")
     * @Assert\Length(
     *      max = "150",
     *      maxMessage = "La description ne doit pas contenir plus de {{ limit }} caractères"
     * )
     */
    protected $description;
    
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=80, unique=true)
     */
    protected $slug;

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
     * Set name
     *
     * @param string $name
     *
     * @return Forum
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Forum
     */
    public function setdescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getdescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Forum
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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
}
