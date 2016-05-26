<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;

/**
 * CategoryControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class CategoryControllerTest extends TestBase
{
    public function testNewCategoryAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('discutea_forum_create_category');
        $this->tryUrlAdmin($url);
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Nom de la catégorie")')->count() > 0 );
        
    }

    public function testEditCategoryAction()
    {   
        $this->addFixtruresCategory();
        $categories = $this->em->getRepository('DForumBundle:Category')->findAll();
        $this->assertCount(3, $categories);
        
        $category = $this->em->getRepository('DForumBundle:Category')->findOneByName('userCategoryTest');
        
        if ($category !== NULL) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_edit_category', array('id' => $category->getId()));
            $this->tryUrlAdmin($url);
            $this->assertTrue( $this->adminCrawler->filter('html:contains("Nom de la catégorie")')->count() > 0 );
        } else {
            $this->assertTrue( 1 == 2 );
        }
    }

    public function testRemoveCategoryAction()
    {
        $this->addFixtruresCategory();
        $categories = $this->em->getRepository('DForumBundle:Category')->findAll();
        $this->assertCount(3, $categories);
        
        $category = $this->em->getRepository('DForumBundle:Category')->findOneByName('adminCategoryTest');
        
        if ($category !== NULL) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_remove_category', array('id' => $category->getId()));
            $this->tryUrlAdmin($url);
            $this->assertTrue( $this->adminCrawler->filter('html:contains("Suppression d\'une catégorie")')->count() > 0 );
        } else {
            $this->assertTrue( 1 == 2 );
        }
    }
}
