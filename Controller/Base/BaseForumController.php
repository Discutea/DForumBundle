<?php

namespace Discutea\DForumBundle\Controller\Base;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Discutea\DForumBundle\Controller\Base\BaseController;


/**
 * BaseForumController 
 * 
 * This class contains useful methods for the proper functioning of the forum controller and not method actions.
 * This class extends BaseController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BaseForumController extends BaseController
{

    /**
     * Create form for remove forum
     * 
     * @return object Symfony\Component\Form\Form
     */
    protected function getFormRemoverForum() {
        $form = $this->createFormBuilder()
            ->add('movedTo', ChoiceType::class, array(
                'choices' => $this->getAllForums(),
                'choices_as_values' => true,
            ))
            ->add('purge', CheckboxType::class, array(
                'label'    => 'discutea.forum.forum.removeall.label',
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
     * @return array forum's list ordoned
     */
    protected function getAllForums() {
        $categories = $this->getEm()->getRepository('DForumBundle:Category')->findBy(array(), array('position' => 'asc', ));
        $fors = array();

        foreach ($categories as $category) {
            $tmpForums = array();
            $forums = $category->getForums();

            foreach ($forums as $forum) {
                $tmpForums[$forum->getName()] = $forum->getId();
            }

            $fors[$category->getName()] = $tmpForums;
        }
        
        return $fors;
    }
}
