<?php
/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Discutea\DForumBundle\Tests;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestBase extends KernelTestCase
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
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
