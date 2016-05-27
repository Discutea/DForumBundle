<?php
namespace Discutea\DForumBundle\Tests\Controller;

use Discutea\DForumBundle\Tests\TestBase;

/**
 * LabelControllerTest
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class LabelControllerTest extends TestBase
{

    public function testSolvedAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);
        
        foreach ($forums as $forum) {
            $topics = $forum->getTopics();
            foreach ($topics as $topic) {
                $url = $this->client->getContainer()->get('router')->generate('discutea_label_solved', array('slug' => $topic->getSlug()));
                if ($topic->getSlug() == 'admintopictest') {
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                } elseif ($topic->getSlug() == 'moderatortopictest') {
                    $this->tryUrl(302, 403, 403, 302, 302, $url);
                    $this->tryUrl(302, 403, 403, 302, 302, $url);
                }
                // phpunit error $token for testing users permissions return false
            }
        }  
    }
    
    public function testPinnedAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);
        
        foreach ($forums as $forum) {
            $topics = $forum->getTopics();
            foreach ($topics as $topic) {
                $url = $this->client->getContainer()->get('router')->generate('discutea_label_pinned', array('slug' => $topic->getSlug()));
                $this->tryUrl(302, 403, 403, 403, 302, $url);
                $this->tryUrl(302, 403, 403, 403, 302, $url);
            }
        }
    }

    public function testClosedAction()
    {
        $this->addFixtruresCategory();
        $this->addFixtruresForum();
        $this->addFixtruresTopic();
        
        $forums = $this->em->getRepository('DForumBundle:Forum')->findAll();
        $this->assertCount(3, $forums);
        
        foreach ($forums as $forum) {
            $topics = $forum->getTopics();
            foreach ($topics as $topic) {
                $url = $this->client->getContainer()->get('router')->generate('discutea_label_closed', array('slug' => $topic->getSlug()));
                if ($topic->getSlug()  == 'admintopictest') {
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                    $this->tryUrl(302, 403, 403, 403, 302, $url);
                } else {
                    $this->tryUrl(302, 403, 403, 302, 302, $url);
                    $this->tryUrl(302, 403, 403, 302, 302, $url);
                }
                
            }
        }
    }
}
