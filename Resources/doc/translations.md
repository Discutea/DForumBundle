Creation du listener pour doctrine translation

<?php

// file: src/Acme/DemoBundle/Listener/DoctrineExtensionListener.php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineExtensionListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function onLateKernelRequest(GetResponseEvent $event)
    {
        $translatable = $this->container->get('gedmo.listener.translatable');
        $translatable->setTranslatableLocale($event->getRequest()->getLocale());
    }

    public function onConsoleCommand()
    {
        $this->container->get('gedmo.listener.translatable')
            ->setTranslatableLocale($this->container->get('translator')->getLocale());
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $loggable = $this->container->get('gedmo.listener.loggable');
            $loggable->setUsername($securityContext->getToken()->getUsername());
        }
    }
}

#app/services.yml

    # KernelRequest listener
    extension.listener:
        class: AppBundle\Listener\DoctrineExtensionListener
        calls:
            - [ setContainer, [ "@service_container" ] ]
        tags:
            # translatable sets locale after router processing
            - { name: kernel.event_listener, event: kernel.request, method: onLateKernelRequest, priority: -10 }
            # loggable hooks user username if one is in security context
            - { name: kernel.event_listener, event: kernel.request, method: onKernelRequest }
            # translatable sets locale such as default application locale before command execute
            - { name: kernel.event_listener, event: console.command, method: onConsoleCommand, priority: -10 }

    gedmo.listener.translatable:
        class: Gedmo\Translatable\TranslatableListener
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ "@annotation_reader" ] ]
            - [ setDefaultLocale, [ %locale% ] ]
            - [ setTranslationFallback, [ false ] ]

#app/config.yml
    orm:
        mappings:
            gedmo_translatable:
                type: annotation
                prefix: Gedmo\Translatable\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                alias: GedmoTranslatable # (optional) it will default to the name set for the mapping
                is_bundle: false