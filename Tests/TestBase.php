<?php

namespace Discutea\DForumBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Component\Console\Input\StringInput;

class TestBase extends WebTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $client;
    
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
        $container = $this->client->getKernel()->getContainer();
        
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        

        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $executor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $executor->purge();

        $loader = new \Doctrine\Common\DataFixtures\Loader;
        $fixtures = new \Discutea\DForumBundle\Tests\Fixtures\FosFixtures();
        $fixtures->setContainer($container);
        $loader->addFixture($fixtures);
        $executor->execute($loader->getFixtures());
        
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        self::runCommand('doctrine:database:drop --force');
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

    protected function tryUrl($httpCode, $url) {
        $this->client->request('GET', $url);
        $this->assertEquals($httpCode, $this->client->getResponse()->getStatusCode()); 
    }
}
