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
     *
     * @Route("/", name="discutea_forum_homepage")
     * 
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function indexAction()
    {
        $categories = $this->getEm()
            ->getRepository('DForumBundle:Category')
            ->findBy(array(), array('position' => 'asc', ))
        ;

        return $this->render('DForumBundle:index.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * 
     * @Route("/forum/new/{id}", name="discutea_forum_create_forum")
     * @ParamConverter("category")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $category Discutea\DForumBundle\Entity\Category
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function newForumAction(Request $request, Category $category)
    {

        $forum = new Forum();
        $forum->setCategory($category);
        
        $form = $this->createForm(ForumType::class, $forum);

        if ($form->handleRequest($request)->isValid()) {
            $this->getEm()->persist($forum);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.created'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }

        return $this->render('DForumBundle:Admin/forum.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("forum/edit/{id}", name="discutea_forum_edit_forum")
     * @ParamConverter("forum")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $forum Discutea\DForumBundle\Entity\Forum
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */
    public function editForumAction(Request $request, Forum $forum)
    {
        
        $form = $this->createForm(ForumType::class, $forum);

        if ($form->handleRequest($request)->isValid()) {
            $this->getEm()->persist($forum);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.edit'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }

        return $this->render('DForumBundle:Admin/forum.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @Route("forum/remove/{id}", name="discutea_forum_remove_forum")
     * @ParamConverter("forum")
     * @Security("is_granted('ROLE_ADMIN')")
     * 
     * @param object $request Symfony\Component\HttpFoundation\Request
     * @param objct $forum Discutea\DForumBundle\Entity\Forum
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse moderator's dashboard
     * @return objet Symfony\Component\HttpFoundation\Response
     * 
     */

    public function removeForumAction(Request $request, Forum $forum)
    {

        $form = $this->createForm(RemoveForumType::class);
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->getData()['purge'] === false) {
                $newFor = $this->getEm()->getRepository('DForumBundle:Forum')->find($form->getData()['movedTo']) ;
                
                foreach ($forum->getTopics() as $topic) { $topic->setForum($newFor); }
                
                $this->getEm()->flush();
                $this->getEm()->clear();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.movedtopics'));
            }
            
            
            $forum = $this->getEm()->getRepository('DForumBundle:Forum')->find($forum->getId()); // Fix detach error
            $this->getEm()->remove($forum);
            $this->getEm()->flush();

            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.forum.delete'));
            return $this->redirect($this->generateUrl('discutea_forum_moderator_dashboard'));
        }
 
        return $this->render('DForumBundle:Admin/remove_forum.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
