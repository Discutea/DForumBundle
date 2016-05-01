<?php

namespace Discutea\DForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="df_trans_forum")
 */
class ForumTranslation
{
    use ORMBehaviors\Translatable\Translation;

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
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

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

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }
}
