<?php

namespace Discutea\DForumBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Discutea\DForumBundle\Entity\Forum;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\CategoryRepository")
 * @ORM\Table(name="df_category")
 */
class Category 
{
use ORMBehaviors\Translatable\Translatable;
    /**
     * @var smallint
     *
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="disp_position", type="integer", options={"unsigned"=true})
     */
    protected $position = 0;

    /**
     * @ORM\OneToMany(targetEntity="Discutea\DForumBundle\Entity\Forum", mappedBy="category", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "asc"})
     */
    protected $forums;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @ORM\JoinColumn(name="read_authorised_roles")
     */
    protected $readAuthorisedRoles;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->forums = new ArrayCollection();
		$this->translations = new ArrayCollection();
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
     * Set position
     *
     * @param integer $position
     *
     * @return Category
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
     * Add forum
     *
     * @param \Discutea\DForumBundle\Entity\Forum $forum
     *
     * @return Category
     */
    public function addForum(Forum $forum)
    {
        $this->forums[] = $forum;

        return $this;
    }

    /**
     * Remove forum
     *
     * @param \Discutea\DForumBundle\Entity\Forum $forum
     */
    public function removeForum(Forum $forum)
    {
        $this->forums->removeElement($forum);
    }

    /**
     * Get forums
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * Set readAuthorisedRoles
     *
     * @param array $roles
     *
     * @return Category
     */
    public function setReadAuthorisedRoles($role)
    {
        $this->readAuthorisedRoles = $role;
        
        return $this;
    }

    /**
     * Get readAuthorisedRoles
     *
     * @return array
     */
    public function getReadAuthorisedRoles()
    {
        return $this->readAuthorisedRoles;
    }

    /**
     *
     * @param  SecurityContextInterface $security
     * @return bool
     */
    public function isAuthorisedToRead(AuthorizationChecker $security)
    {
        if (0 == count($this->readAuthorisedRoles)) {
            return true;
        }
        
        if ($security->isGranted($this->readAuthorisedRoles)) {
            return true;
        }
        
        return false;
    }

}
