<?php

namespace Discutea\DForumBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Topic;
use Discutea\DForumBundle\Entity\Post;

class TestBase extends WebTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $client;
    
    protected $container;
    
    protected $executor;
    
    protected static $application;

    protected $clientCrawler;
    protected $member1Crawler;
    protected $member2Crawler;
    protected $moderatorCrawler;
    protected $adminCrawler;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
        
        self::bootKernel();
        
        $this->client = self::createClient();
        $this->container = $this->client->getKernel()->getContainer();
        
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $this->executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $this->executor->purge();

        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $fixtures = new \Discutea\DForumBundle\Tests\Fixtures\FosFixtures();
        $fixtures->setContainer($this->container);
        $loader->addFixture($fixtures);
        $this->executor->execute($loader->getFixtures());
        
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
//        self::runCommand('doctrine:database:drop --force');
        $this->em = null; // avoid memory leaks
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();
            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }
        return self::$application;
    }

    protected function doLogin($username, $password) {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => $username,
            '_password'  => $password,
        ));     
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->followRedirect();
    
        return $this->client;
    }

    protected function tryUrlModerator($url) {
        return $this->tryUrl(302, 403, 403, 200, 200, $url);
    }
    
    protected function tryUrlAdmin($url) {
        $this->tryUrl(302, 403, 403, 403, 200, $url);
    }

    protected function tryUrlFull($url) {
        $this->tryUrl(200, 200, 200, 200, 200, $url);
    }
    
    protected function tryUrl($anonCode, $member1Code, $member2Code, $moderatorCode, $adminCode, $url) {
        $this->client = self::createClient();
        $this->clientCrawler = $this->client->request('GET', $url);
        $this->assertEquals($anonCode, $this->client->getResponse()->getStatusCode());
        
        $this->client = $this->doLogin('member1', 'password');
        $this->member1Crawler = $this->member1Crawler = $this->client->request('GET', $url);
        $this->assertEquals($member1Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('member2', 'password');
        $this->member2Crawler = $this->client->request('GET', $url);
        $this->assertEquals($member2Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('moderator', 'password');
        $this->moderatorCrawler = $this->client->request('GET', $url);
        $this->assertEquals($moderatorCode, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('admin', 'password');
        $this->adminCrawler = $this->client->request('GET', $url);
        $this->assertEquals($adminCode, $this->client->getResponse()->getStatusCode());
    }
    
    protected function addFixtruresCategory() {
        $query = $this->em->createQuery('DELETE FROM DForumBundle:Category');
        $query->execute(); 

        $admin = new Category();
        $admin->setName('adminCategoryTest');
        $admin->setReadAuthorisedRoles('ROLE_ADMIN');
        
        $moderator = new Category();
        $moderator->setName('moderatorCategoryTest');
        $moderator->setReadAuthorisedRoles('ROLE_MODERATOR');
        
        $user = new Category();
        $user->setName('userCategoryTest');
        
        $this->em->persist($admin);
        $this->em->persist($moderator);
        $this->em->persist($user);
        $this->em->flush();
    }

    protected function addFixtruresForum() {
        $query = $this->em->createQuery('DELETE FROM DForumBundle:Forum');
        $query->execute(); 
        
        $names = array('admin', 'moderator', 'user');
        
        foreach ($names as $name) {
            
            $category = $this->em->getRepository('DForumBundle:Category')->findOneByName($name.'CategoryTest');
            
            if ($category === NULL) {
                $this->assertTrue( 1 == 2 );
                die();
            }
        
            $entity = 'entity'.$name;
            $entity = new Forum();
            $entity->setName($name.'ForumTest');
            $entity->setDescription($name.'ForumTest description');
            $entity->setCategory($category); 
            $this->em->persist($entity);
            $this->em->flush();
            $this->em->clear();
        }
    }

    protected function addFixtruresTopic() {
        $query = $this->em->createQuery('DELETE FROM DForumBundle:Topic');
        $query->execute(); 
        
        $names = array('admin', 'moderator', 'member1', 'member2');

        foreach ($names as $name) {
            
            if ( ($name == 'admin') || ($name == 'moderator') ) {
                $forum = $this->em->getRepository('DForumBundle:Forum')->findOneBySlug($name.'forumtest');
            } else {
                $forum = $this->em->getRepository('DForumBundle:Forum')->findOneBySlug('userforumtest');
            }
            
            if ($forum === NULL) {
                $this->assertTrue( 1 == 2 );
                die();
            }
            
            $user = $this->em->getRepository('DForumBundleUsersEntity:Users')->findOneByUsername($name);

            if ($user === NULL) {
                $this->assertTrue( 1 == 2 );
                die();
            }
            
            $entity = 'entity'.$name;
            $entity = new Topic();
            $entity->setTitle($name.'TopicTest');
            $entity->setDate(new \Datetime());
            $entity->setForum($forum);
            $entity->setUser($user);
 
            $post = new Post();
            $post->setContent($name . 'first post');
            $post->setTopic($entity);
            $post->setPoster($entity->getUser());
        
            $this->em->persist($entity);
            $this->em->persist($post);
            $this->em->flush();
            $this->em->clear();
        }
    }
}
