<?php

namespace Discutea\DForumBundle\Entity;

use Discutea\DForumBundle\Entity\Model\Category as BaseCategory;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 */
class Category extends BaseCategory
{

}
