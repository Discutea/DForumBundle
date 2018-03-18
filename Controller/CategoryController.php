<?php

namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Discutea\DForumBundle\Form\Type\Remover\RemoveCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Form\Type\CategoryType;

/**
 * CategoryController 
 * 
 * This class contains actions methods for forum.
 * This class extends BaseCategoryController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class CategoryController extends BaseController
{
    /**
     * @Route("category/new", name="discutea_forum_create_category")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, array('roles' => $this->getRolesList()));

        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            $em = $this->getEm();
            $em->persist($category);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.created'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }

        return $this->render('@DForum/Admin/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category/edit/{id}", name="discutea_forum_edit_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCategoryAction(Request $request, Category $category)
    {   
        $form = $this->createForm(CategoryType::class, $category, array('roles' => $this->getRolesList()));

        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            $em = $this->getEm();
            $em->persist($category);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.edit'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }

        return $this->render('@DForum/Admin/category.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("category/remove/{id}", name="discutea_forum_remove_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeCategoryAction(Request $request, Category $category)
    {
        $form = $this->createForm(RemoveCategoryType::class);

        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            $em = $this->getEm();
            if ($form->getData()['purge'] === false) {
                
                $newCat = $em->getRepository(Category::class)->find($form->getData()['movedTo']) ;
                
                foreach ($category->getForums() as $forum) 
                { 
                    $forum->setCategory($newCat); 
                }

                $em->flush();
                $em->clear();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.movedforums'));
            }
            
            $category = $em->getRepository(Category::class)->find($category->getId()); // Fix detach error;
            $em->remove($category);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.delete'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }
 
        return $this->render('@DForum/Admin/remove_category.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
