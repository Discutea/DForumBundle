<?php
namespace Discutea\DForumBundle\Tests\Entity;

use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Tests\Entity\CategoryTest;
use Discutea\DForumBundle\Entity\Forum;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForumTest extends WebTestCase
{
    private $forum;
    private $category;

    public function __construct(Category $category = null) {
        $this->forum = new Forum();
        $this->forum->setName('test phpunit');
        $this->forum->setPosition(5);
        $this->forum->setDescription('Simple description phpunit');
        $this->forum->setImage('http://test.tld/img.jpg/');
        if ($category !== null) {
            $this->category = $category;
        } else {
            $category = new CategoryTest();
            $this->category = $category->getCategory();
        }
        $this->forum->setCategory($this->category);
	}

    public function testSettersAndGetters()
    {
        $this->assertEquals('test phpunit', $this->forum->getName());
        $this->assertEquals('Simple description phpunit', $this->forum->getDescription());
        $this->assertEquals(5, $this->forum->getPosition());
        $this->assertEquals('http://test.tld/img.jpg/', $this->forum->getImage());
    }
}
