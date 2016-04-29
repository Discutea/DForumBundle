<?php

namespace Discutea\DForumBundle\Entity;

use Discutea\DForumBundle\Entity\Model\Forum as BaseForum;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\ForumRepository")
 * @ORM\Table(name="forums")
 */
class Forum extends BaseForum
{
}
