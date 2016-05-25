<?php

namespace Discutea\DForumBundle\Tests\tests\src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Discutea\DForumBundle\Entity\Model\BasePost;

/**
 * 
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\PostRepository")
 * @ORM\Table(name="df_post")
 * 
 */
class Post extends BasePost
{

}
