<?php

namespace Discutea\DForumBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\User\UserInterface;

class ForumListener
{
 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if (!$entity instanceof UserInterface) {
            return;
        }
        
        $em = $args->getEntityManager();
        $topicRepo = $em->getRepository('DForumBundle:Topic');
                
        foreach ($topicRepo->findByUser($entity) as $topic) {
            $topic->setUser(NULL);
            $em->persist($topic);
        }
               
        $postRepo = $em->getRepository('DForumBundle:Post');
        
        foreach ($postRepo->findByPoster($entity) as $post) {
            $post->setPoster(NULL);
            $em->persist($post);
        }
                
        foreach ($postRepo->findByUpdatedBy($entity) as $post) {
            $post->setUpdatedBy(NULL);
            $em->persist($post);
        }
        $em->flush(); 
    }

}
