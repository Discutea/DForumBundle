<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;

/**
 * PostControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class PostControllerTest extends TestBase
{

    public function testPostAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        $this->addFixtruresPost();
        
        $topics = $this->em->getRepository('DForumBundle:Topic')->findAll();
        $this->assertCount(4, $topics);
        
        foreach ($topics as $topic) {
            $slug = $topic->getSlug();
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_post', array('slug' => $slug));
            if ($slug  == 'admintopictest') {
                $this->tryUrlAdmin($url);
            } elseif ($slug  == 'moderatortopictest') {
                $this->tryUrlModerator($url);
                $this->assertTrue($this->moderatorCrawler->filter('html:contains("replyPost")')->count() > 0 );
            } else {
                $this->tryUrlFull($url);
                $this->assertTrue( $this->clientCrawler->filter('html:contains("replyPost")')->count() > 0 );
                $this->assertTrue( $this->member1Crawler->filter('html:contains("replyPost")')->count() > 0 );
                $this->assertTrue( $this->member2Crawler->filter('html:contains("replyPost")')->count() > 0 );
                $this->assertTrue( $this->moderatorCrawler->filter('html:contains("replyPost")')->count() > 0 );
            }
           $this->assertTrue($this->adminCrawler->filter('html:contains("replyPost")')->count() == 1 );
        }
    }
}
