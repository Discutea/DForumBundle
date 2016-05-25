<?php
namespace Discutea\DForumBundle\Tests\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FosFixtures implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $userManager = $this->container->get('fos_user.user_manager');
        $userNames = array('admin', 'moderator', 'member1', 'member2');
       
        foreach ($userNames as $nick) {
            $user = $userManager->createUser();
            $user->setUsername($nick);
            $user->setEmail($nick . '@test.discutea.com');
            $user->setPlainPassword('password');
            $user->setEnabled(true);                   
            if ($nick == 'admin') {
                $user->addRole('ROLE_ADMIN');
            } elseif ($nick == 'moderator') {
                $user->addRole('ROLE_MODERATOR');
            }
            
            $manager->persist($user);
            $manager->flush();
        }
    }
    
}
