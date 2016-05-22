<?php

namespace Discutea\DForumBundle\Controller\Base;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Controller\Base\BaseController;


/**
 * BaseForumController 
 * 
 * This class contains useful methods for the proper functioning of the category controller and not method actions.
 * This class extends BaseController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BaseCategoryController extends BaseController
{
    /**
     * Role listing
     * 
     * @return array $roles
     */
    protected function getRolesList()
    {
        $rolesList = $this->get('service_container')->getParameter('security.role_hierarchy.roles');
        $roles = array();
        $roles['Aucun'] = NULL;
        foreach ($rolesList as $roleParent) {
            foreach ($roleParent as $roleChild) {
                $roles[$roleChild] = $roleChild;  
            }  
        }
        return $roles;
    }
}
