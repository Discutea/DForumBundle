<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;

class CategoryControllerTest extends TestBase
{
    public function testSaveCategory()
    {
        $category = $this->addNewCategory('NewCategoryTest', 1, null, true, true);
        $this->assertTrue(is_numeric($category->getId()));
        $this->assertSame('NewCategoryTest', $category->getName());
    }

}
