<?php

namespace Discutea\DForumBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestBase extends WebTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $client;
    
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
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
        $this->em = null; // avoid memory leaks
    }
}
