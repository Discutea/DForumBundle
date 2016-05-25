<?php

namespace Discutea\DForumBundle\Tests\tests\src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Discutea\DForumBundle\Entity\Model\BaseTopic;

/**
 *
 * @ORM\Entity(repositoryClass="Discutea\DForumBundle\Repository\TopicRepository")
 * @ORM\Table(name="df_topic")
 * 
 */
class Topic extends BaseTopic
{

}
