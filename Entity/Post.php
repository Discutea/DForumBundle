<?php

namespace Discutea\DForumBundle\Entity;

use Discutea\DForumBundle\Entity\Model\Post as BasePost;
use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * 
 */
class Post extends BasePost
{

}
