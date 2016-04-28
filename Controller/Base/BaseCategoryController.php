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
     * Create form for remove forum
     * 
     * @param object $category Discutea\DForumBundle\Entity\Category
     * 
     * @return object Symfony\Component\Form\Form
     */
    protected function getFormRemoverCategory(Category $category) {
        $form = $this->createFormBuilder()
            ->add('movedTo', ChoiceType::class, array(
                'choices' => $this->getAllCategories($category),
                'choices_as_values' => true,
            ))
            ->add('purge', CheckboxType::class, array(
                'label'    => 'discutea.forum.category.removeall.label',
                'required' => false,
            ))
            ->add('save', SubmitType::class)
            ->getForm();
        
        return $form;
    }
    
    /**
     * 
     * Listing all forums order by categories
     * 
     * @param object $cats Discutea\DForumBundle\Entity\Category
     * 
     * @return array categorie's list ordoned
     */
    protected function getAllCategories(Category $cat) {
        $categories = $this->getEm()->getRepository('DForumBundle:Category')->findBy(array(), array('position' => 'asc', ));

        $cats = array();
        foreach ($categories as $category) {
            if ($category !== $cat) {
                $cats[$category->getName()] = $category->getId();  
            }
        }

        return $cats;
    }

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
