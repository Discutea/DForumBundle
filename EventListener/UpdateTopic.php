<?php

namespace Discutea\DForumBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Discutea\DForumBundle\Entity\Post;

/**
 * @author David Verdier <contact@discutea.com>
 * 
 * Class UpdateTopic
 * Type: Doctrine Listener
 * service name: topic.listener.updatelastpost
 * 
 * METHODS LIST:
 * postPersist(Doctrine\ORM\Event\LifecycleEventArgs $args) Update lastPost on postPersist
 * postRemove(Doctrine\ORM\Event\LifecycleEventArgs $args) Update lastPost on postRemove
 * 
 */
class UpdateTopic
{
    /**
     * infos: Update LastPost on postPersist
     *
     * @param class Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return sql  topic.last_post
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Post) {
            return;
        }

        $em = $args->getEntityManager();
        $topic = $em->getRepository('DForumBundle:Topic')->find($entity->getTopic());
        $topic->setLastPost($entity->getDate());
        $em->flush();

    }

    /**
     * infos: Update LastPost on postRemove
     *
     * @param class Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return sql  topic.last_post
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Post) {
            return;
        }

        $em = $args->getEntityManager();
        $topic = $em->getRepository('DForumBundle:Topic')->find($entity->getTopic());
        $post = $topic->getPosts()->last();
        
        // if drop a unique post or first post in topic remove topic
        if ($post === null) {
            $em->remove($topic);
        } else {
            $topic->setLastPost($post->getDate());
        }
        $em->flush();
    }
}
