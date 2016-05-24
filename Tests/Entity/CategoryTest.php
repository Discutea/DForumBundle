<?php
namespace Discutea\DForumBundle\Tests\Entity;

use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Entity\Forum;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryTest extends WebTestCase
{
    private $category;

    public function __construct() {
        $this->category = new Category();
        $this->category->setName('hello world');
        $this->category->setPosition(567);
        $this->category->setReadAuthorisedRoles('ROLE_MODERATOR');
    }

    public function testSettersAndGetters()
    {
        $this->assertEquals('hello world', $this->category->getName());
        $this->assertEquals('ROLE_MODERATOR', $this->category->getReadAuthorisedRoles());
        $this->assertEquals(567, $this->category->getPosition());
    }

    public function getCategory()
    {	
        return $this->category;
    }
}
