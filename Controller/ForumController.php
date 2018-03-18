<?php

namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Form\Type\ForumType;
use Discutea\DForumBundle\Form\Type\Remover\RemoveForumType;

/**
 * ForumController 
 * 
 * This class contains actions methods for forum.
 * This class extends BaseForumController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class ForumController extends BaseController
{

    /**
     * @Route("", name="discutea_forum_homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $categories = $this->getEm()
            ->getRepository(Category::class)
            ->findBy(array(), array('position' => 'asc', ))
        ;

        return $this->render('@DForum/index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * @Route("/forum/new/{id}", name="discutea_forum_create_forum")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Category $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newForumAction(Request $request, Category $category)
    {
        $forum = new Forum();
        $forum->setCategory($category);
        
        $form = $this->createForm(ForumType::class, $forum);

        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            $em = $this->getEm();
            $em->persist($forum);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.created'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }

        return $this->render('@DForum/Admin/forum.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("forum/edit/{id}", name="discutea_forum_edit_forum")
     * @ParamConverter("forum")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Forum $forum
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editForumAction(Request $request, Forum $forum)
    {
        $form = $this->createForm(ForumType::class, $forum);

        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            $em = $this->getEm();
            $em->persist($forum);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.edit'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }

        return $this->render('@DForum/Admin/forum.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("forum/remove/{id}", name="discutea_forum_remove_forum")
     * @ParamConverter("forum")
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Forum $forum
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function removeForumAction(Request $request, Forum $forum)
    {
        $form = $this->createForm(RemoveForumType::class);
        $em = $this->getEm();
        $form->handleRequest($request);
        
        if (($form->isSubmitted()) && ($form->isValid())) 
        {
            if ($form->getData()['purge'] === false) {
                $newFor = $em->getRepository(Forum::class)->find($form->getData()['movedTo']) ;
                
                foreach ($forum->getTopics() as $topic) { $topic->setForum($newFor); }
                
                $em->flush();
                $em->clear();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.movedtopics'));
            }
            
            
            $forum = $em->getRepository(Forum::class)->find($forum->getId()); // Fix detach error
            $em->remove($forum);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.delete'));
            return $this->redirect($this->generateUrl('discutea_forum_admin_dashboard'));
        }
 
        return $this->render('@DForum/Admin/remove_forum.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
