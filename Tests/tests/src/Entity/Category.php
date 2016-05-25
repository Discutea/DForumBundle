<?php
namespace Discutea\DForumBundle\Tests\tests\src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Discutea\DForumBundle\Entity\Model\BaseCategory;

/**
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\CategoryRepository")
 * @ORM\Table(name="df_category")
 */
class Category extends BaseCategory
{

}
