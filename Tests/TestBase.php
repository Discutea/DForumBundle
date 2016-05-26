<?php

namespace Discutea\DForumBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Discutea\DForumBundle\Entity\Category;

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
    
    protected function tryUrl($anonCode, $member1Code, $member2Code, $moderatorCode, $adminCode, $url) {
        $this->client = self::createClient();
        $this->client->request('GET', $url);
        $this->assertEquals($anonCode, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('member1', 'password');
        $this->client->request('GET', $url);
        $this->assertEquals($member1Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('member2', 'password');
        $this->client->request('GET', $url);
        $this->assertEquals($member2Code, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('moderator', 'password');
        $this->client->request('GET', $url);
        $this->assertEquals($moderatorCode, $this->client->getResponse()->getStatusCode());

        $this->client = $this->doLogin('admin', 'password');
        $this->client->request('GET', $url);
        $this->assertEquals($adminCode, $this->client->getResponse()->getStatusCode());
    }
    
    protected function addFixtruresCategory() {
        $query = $this->em->createQuery('DELETE FROM DForumBundle:Category');
        $query->execute(); 

        $admin = new Category();
        $admin->setName('admin');
        $admin->setReadAuthorisedRoles('ROLE_ADMIN');
        
        $moderator = new Category();
        $moderator->setName('moderator');
        $moderator->setReadAuthorisedRoles('ROLE_MODERATOR');
        
        $user = new Category();
        $user->setName('user');
        
        $this->em->persist($admin);
        $this->em->persist($moderator);
        $this->em->persist($user);
        $this->em->flush();
    }
}
