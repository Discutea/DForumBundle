<?php
namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseCategoryController;
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
class CategoryController extends BaseCategoryController
{
    /**
     * 
     * @Route("forum/category/create", name="discutea_forum_create_category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function newCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, array('roles' => $this->getRolesList()));

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.created'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }

        $form = $form->createView();

        return $this->render('DForumBundle:Admin/category.html.twig', array(
            'form' => $form
        ));
    }

    /**
     * 
     * @Route("forum/category/edit/{id}", name="discutea_forum_edit_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DForumBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function editCategoryAction(Request $request, Category $category)
    {   
        $form = $this->createForm(CategoryType::class, $category, array('roles' => $this->getRolesList()));

        if ($form->handleRequest($request)->isValid()) {
            $this->getEm()->persist($category);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.edit'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }
        
        $form = $form->createView();

        return $this->render('DForumBundle:Admin/category.html.twig', array(
            'form' => $form
        ));
    }

    /**
     * 
     * @Route("forum/category/{id}", name="discutea_forum_remove_category")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DForumBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function removeCategoryAction(Request $request, Category $category)
    {

        $form = $this->createForm(RemoveCategoryType::class);

        if ($form->handleRequest($request)->isValid()) {
            if ($form->getData()['purge'] === false) {
                $forums = $category->getForums();
                $newCat = $this->getEm()->getRepository('DForumBundle:Category')->find($form->getData()['movedTo']) ;
                
                foreach ($forums as $forum) { $forum->setCategory($newCat); }

                $this->getEm()->flush();
                $this->getEm()->clear();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.movedforums'));
            }
            
            $category = $this->getEm()->getRepository('DForumBundle:Category')->find($category->getId()); // Fix detach error;
            $this->getEm()->remove($category);
            $this->getEm()->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.category.delete'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }
 
        return $this->render('DForumBundle:Admin/remove_category.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
