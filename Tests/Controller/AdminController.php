<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;


/**
 * AdminControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class AdminControllerTest extends TestBase
{

    public function testIndexAction()
    {
        $url = $this->client->getContainer()->get('router')->generate('discutea_forum_admin_dashboard');
        $this->tryUrlModerator($url);

        $this->assertTrue( $this->adminCrawler->filter('html:contains("Créer une catégorie")')->count() > 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
        
        $this->addFixtruresCategory();
        $this->tryUrlModerator($url);
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Créer une catégorie")')->count() > 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
        
        $this->addFixtruresForum();
        $this->tryUrlModerator($url);
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Créer une catégorie")')->count() > 0 );
        $this->assertTrue( $this->moderatorCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
        $this->assertTrue( $this->adminCrawler->filter('html:contains("Les derniers messages")')->count() > 0 );
    }
}
