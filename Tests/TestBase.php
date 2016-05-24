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
use Discutea\DForumBundle\Tests\tests\Entity\User;
use Discutea\DForumBundle\Entity\Category;

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

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    protected function addNewUser($username, $email, $password, $persist = true, $andFlush = true)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);

        if ($persist === true) {
            $this->em->persist($user);
            if ($andFlush === true) {
                $this->em->flush();
                $this->em->refresh($user);
            }
        }

        return $user;
    }

    protected function addFixturesForUsers()
    {
        $userNames = array('admin', 'moderator', 'strategy', 'harakiri');
        $users = array();
        foreach ($userNames as $username) {
            $users[$username] = $this->addNewUser($username, $username . '@discutea.com', 'password', true, false);
        }
        $this->em->flush();

        return $users;
    }

    protected function addNewCategory($name, $position, $readAuthorisedRoles = null, $persist = true, $andFlush = true)
    {
        $category = new Category();
        $category->setName($name);
        $category->setPosition($position);
        $category->setReadAuthorisedRoles($readAuthorisedRoles);

        if ($persist === true) {
            $this->em->persist($category);
            if ($andFlush === true) {
                $this->em->flush();
                $this->em->refresh($category);
            }
        }

        return $category;
    }

}
