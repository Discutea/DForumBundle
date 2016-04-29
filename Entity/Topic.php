<?php

namespace Discutea\DForumBundle\Entity;

use Discutea\DForumBundle\Entity\Model\Topic as BaseTopic;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\TopicRepository")
 * @ORM\Table(name="d_topics")
 * 
 */
class Topic extends BaseTopic
{   

}
