<?php

namespace Discutea\DForumBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * BaseController is a sample class for use recurring variables in controllers.
 *
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BaseController extends Controller
{

    /**
     * @var object $em Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var object $paginator Discutea\DForumBundle\Component\Pagin
     */
    protected $paginator;

    /**
     * @var object $authorizationChecker Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var object $translator Symfony\Component\Translation\DataCollectorTranslator
     */
    protected $translator;

    /**
     * @return object Doctrine\ORM\EntityManager
     */
    protected function getEm() {
        if  (null === $this->em) {
            $this->em = $this->getDoctrine()->getManager();
        }
        
        return $this->em;
    }

    /**
     * @return object Discutea\DForumBundle\Component\Pagin
     */
    protected function getPaginator() {
        if  (null === $this->paginator) {
            $this->paginator = $this->get('discutea.forum.pagin');
        }
        
        return $this->paginator;
    }

    /**
     * @return object Symfony\Component\Security\Core\Authorization\AuthorizationChecker
     */
    protected function getAuthorization() {
        if  (null === $this->authorizationChecker) {
            $this->authorizationChecker = $this->get('security.authorization_checker');
        }
        
        return $this->authorizationChecker;
    }

    /**
     * 
     * @return object Symfony\Component\Translation\DataCollectorTranslator
     */
    protected function getTranslator() {
        if  (null === $this->translator) {
            $this->translator = $this->get('translator');
        }
        
        return $this->translator;
    }

    /**
     * @return array
     */
    protected function getRolesList()
    {
        $rolesList = $this->get('service_container')->getParameter('security.role_hierarchy.roles');
        $roles = array();
        foreach ($rolesList as $roleParent) {
            foreach ($roleParent as $roleChild) {
                $roles[$roleChild] = $roleChild;  
            }  
        }
        return $roles;
    }
}
