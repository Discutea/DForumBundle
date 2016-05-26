<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;


/**
 * ForumControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class ForumControllerTest extends TestBase
{

    public function testIndexAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('discutea_forum_homepage');
        $this->tryUrlFull($url); //start test content if empty
        $this->addFixtruresCategory();
        $this->tryUrlFull($url); // test category does'nt empty
        
        $this->assertTrue( $this->clientCrawler->filter('html:contains("userCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->member1Crawler->filter('html:contains("userCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("userCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("userCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->clientCrawler->filter('html:contains("adminCategoryTest")')->count() <= 0 );
        $this->assertTrue( $this->clientCrawler->filter('html:contains("moderatorCategoryTest")')->count() <= 0 );
        $this->assertTrue( $this->member2Crawler->filter('html:contains("adminCategoryTest")')->count() <= 0 );
        $this->assertTrue( $this->member1Crawler->filter('html:contains("moderatorCategoryTest")')->count() <= 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("adminCategoryTest")')->count() <= 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("moderatorCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("adminCategoryTest")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("moderatorCategoryTest")')->count() > 0 );
        
        $this->addFixtruresForum();
        $this->tryUrlFull($url); // test forums does'nt empty
        
        $this->assertTrue( $this->clientCrawler->filter('html:contains("userForumTest")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("adminForumTest")')->count() > 0 );
        $this->assertTrue( $this->clientCrawler->filter('html:contains("adminForumTest")')->count() <= 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("adminForumTest")')->count() <= 0 );
        $this->assertTrue( $this->member2Crawler->filter('html:contains("moderatorForumTest")')->count() <= 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("moderatorForumTest")')->count() > 0 );

    }

    public function testNewForumAction()
    {
        $this->addFixtruresCategory();
        $category = $this->em->getRepository('DForumBundle:Category')->findOneByName('userCategoryTest');
        
        if ($category !== NULL) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_create_forum', array('id' => $category->getId()));
            $this->tryUrlAdmin($url);
        } else {
            $this->assertTrue( 1 == 2 );
        }
        
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Sélectionner la catégorie")')->count() > 0 );
        
    }

    public function testEditForumAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $forum = $this->em->getRepository('DForumBundle:Forum')->findOneBySlug('adminforumtest');
        if ($forum !== NULL) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_edit_forum', array('id' => $forum->getId()));
            $this->tryUrlAdmin($url);
        } else {
            $this->assertTrue( 1 == 2 );
        }
        
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Déscription du forum")')->count() > 0 );
    }
    
    public function testRemoveForumAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $forum = $this->em->getRepository('DForumBundle:Forum')->findOneBySlug('userforumtest');
        if ($forum !== NULL) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_remove_forum', array('id' => $forum->getId()));
            $this->tryUrlAdmin($url);
        } else {
            $this->assertTrue( 1 == 2 );
        }
        
        
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Cocher pour supprimer entièrement les sujets du forum")')->count() > 0 );
    }
    
}
