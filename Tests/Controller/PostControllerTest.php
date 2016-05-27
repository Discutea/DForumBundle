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
        $topics = $this->addFixturesAndFindTopics();
        
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

    public function testDeleteAction()
    {
        $topics = $this->addFixturesAndFindTopics();
        
        $i = 0;
        
        foreach ($topics as $topic) {
            $url = $this->client->getContainer()->get('router')->generate('discutea_forum_post_delete', array('id' => $topic->getId()));
            $posts = $topic->getPosts();
            $categoryName = $topic->getForum()->getCategory()->getName();
            foreach ($posts as $post) {
                if ($categoryName == 'adminCategoryTest') {
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                    $this->tryUrl(404, 404, 404, 404, 404, $url);
                } elseif ($categoryName == 'moderatorCategoryTest') {
                    $this->tryUrl(302, 403, 403, 302, 404, $url);
                    $this->tryUrl(404, 404, 404, 404, 404, $url);
                } else {
                    if ($i === 0) {
                        $this->client = $this->doLogin('admin', 'password');
                        $this->adminCrawler = $this->client->request('GET', $url);
                        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
                        $this->tryUrl(404, 404, 404, 404, 404, $url);
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
        $topics = $this->addFixturesAndFindTopics();
        foreach ($topics as $topic) {
            $posts = $topic->getPosts();
            $categoryName = $topic->getForum()->getCategory()->getName();
            foreach ($posts as $post) {
                $poster = $post->getPoster();
                $url = $this->client->getContainer()->get('router')->generate('discutea_forum_post_edit', array('id' => $post->getId()));
                if ( ($poster == 'admin') && ($categoryName != 'adminCategoryTest') ) {
                    $this->tryUrlModerator($url);
                } else {
                    if ($poster == 'member1') {
                        $this->tryUrl(302, 200, 403, 200, 200, $url);
                        $this->assertTrue( $this->member1Crawler->filter('html:contains("Edition du message")')->count() > 0 );
                    } elseif ($poster == 'member2') {
                        $this->tryUrl(302, 403, 200, 200, 200, $url);
                        $this->assertTrue( $this->member2Crawler->filter('html:contains("Edition du message")')->count() > 0 );
                    } else {
                        $this->tryUrlModerator($url);
                    }
                    $this->assertTrue( $this->adminCrawler->filter('html:contains("Edition du message")')->count() > 0 );
                    $this->assertTrue( $this->moderatorCrawler->filter('html:contains("Edition du message")')->count() > 0 );
                }
            }
        }
    }
    
    private function addFixturesAndFindTopics()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        $this->addFixtruresPost();

        $topics = $this->em->getRepository('DForumBundle:Topic')->findAll();
        $this->assertCount(4, $topics);
        
        return $topics;
    }
}
