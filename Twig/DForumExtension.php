<?php

namespace Discutea\DForumBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class DForumExtension extends \Twig_Extension
{
    
    private $em;
    
    private $request;
    
    private $params;
    
    private $prefixUrl;
    
    public function __construct (EntityManager $em, RequestStack $request, array $params, $prefixUrl) {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->params = $params;
        $this->prefixUrl = $prefixUrl;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('urlsAlternateForum', array($this, 'urlsAlternateForum')),
        );
    }

    public function urlsAlternateForum($forumId)
    {

        $translations = $this->em->getRepository('DForumBundle:ForumTranslation')->findByTranslatable($forumId);
        
        $translate = array();
        
        foreach ($translations as $translation){
            if ( ( $translation->getLocale() != $this->request->getLocale() ) 
                    && ( null !== $translation->getSlug() ) ) {
                $locale = $translation->getLocale();
                $slug = $translation->getSlug();
                $link = '<link rel="alternate" hreflang="' . $locale . '" href="'.$this->prefixUrl . $this->params[$locale].'/forum/cat/'. $slug .'" />';
               array_push($translate, $link);
            }
        }
        
        return $translate;
    }

    public function getName()
    {
        return 'dforumbundle_extension';
    }
}