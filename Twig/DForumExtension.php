<?php

namespace Discutea\DForumBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DForumExtension extends \Twig_Extension
{
    
    private $em;
    
    private $request;

    public function __construct (EntityManager $em, RequestStack $request) {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('urlsAlternateForum', array($this, 'urlsAlternateForum')),
        );
    }

    public function urlsAlternateForum($forumId)
    {
        
        return '';
    }

    public function getName()
    {
        return 'dforumbundle_extension';
    }
}
