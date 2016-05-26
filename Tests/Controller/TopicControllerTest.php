<?php

namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;


/**
 * TopicControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TopicControllerTest extends TestBase
{

    public function testTopicAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);
        
        foreach ($forums as $forum) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_topic', array('slug' => $forum->getSlug()));
            if ($forum->getCategory()->getName()  == 'adminCategoryTest') {
                $this->tryUrlAdmin($url);
            } elseif ($forum->getCategory()->getName()  == 'moderatorCategoryTest') {
                $this->tryUrlModerator($url);
                $this->assertTrue( $this->moderatorCrawler->filter('html:contains("TopicTest")')->count() > 0 );
            } else {
                $this->tryUrlFull($url);
                $this->assertTrue( $this->clientCrawler->filter('html:contains("TopicTest")')->count() > 0 );
                $this->assertTrue( $this->member1Crawler->filter('html:contains("TopicTest")')->count() > 0 );
                $this->assertTrue( $this->member2Crawler->filter('html:contains("TopicTest")')->count() > 0 );
                $this->assertTrue( $this->moderatorCrawler->filter('html:contains("TopicTest")')->count() > 0 );
            }
            $this->assertTrue( $this->adminCrawler->filter('html:contains("TopicTest")')->count() > 0 );
        }
    }

    public function testDeleteAction()
    {
        
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);

        $i = 0;
        
        foreach ($forums as $forum) {
            $topics = $forum->getTopics();
            foreach ($topics as $topic) {
                $url = $this->client->getContainer()->get('router')->generate('discutea_forum_topic_delete', array('id' => $topic->getId()));
                if ($topic->getSlug()  == 'admintopictest') {
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                    $this->tryUrl(404, 404, 404, 404, 404, $url);
                } elseif ($topic->getSlug()  == 'moderatortopictest') {
                    $this->tryUrl(302, 403, 403, 302, 404, $url);
                    $this->tryUrl(404, 404, 404, 404, 404, $url);
                } else {
                    if ($i === 0) {
                        $this->client = $this->doLogin('admin', 'password');
                        $this->adminCrawler = $this->client->request('GET', $url);
                        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                        $this->tryUrl(404, 404, 404, 404, 500, $url);
                    } else {
                        $this->tryUrl(302, 403, 403, 302, 404, $url);
                        $this->tryUrl(404, 404, 404, 404, 404, $url); 
                    }
                    $i++;
                    
                }
            }
 
        }
    }

    public function testEditAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);
        
        foreach ($forums as $forum) {
            $topics = $forum->getTopics();
            foreach ($topics as $topic) {
                $url = $this->client->getContainer()->get('router')->generate('discutea_forum_topic_edit', array('id' => $topic->getId()));
                if ($topic->getSlug()  == 'admintopictest') {
                    $this->tryUrlAdmin($url);
                } else {
                    $this->tryUrlModerator($url);
                    $this->assertTrue( $this->moderatorCrawler->filter('html:contains("Sélectionner un forum")')->count() > 0 );
                }
                $this->assertTrue( $this->adminCrawler->filter('html:contains("Sélectionner un forum")')->count() > 0 );
            }
        }
    }

}
