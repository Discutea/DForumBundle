<?php
namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @author David Verdier <contact@discutea.com>
 * 
 * Class AdminController
 * 
 * METHODS LIST:
 * indexAction() return discutea_forum_moderator_dashboard
 * newCategoryAction(Request $request) Create a new category
 * editCategoryAction(Request $request, $id) Edit a category
 * removeCategoryAction(Request $request, $id) Drop a category
 * newForumAction(Request $request, $cid) Create a new forum
 * editForumAction(Request $request, $id) Edit a forum
 * removeForumAction(Request $request, $id) Drop a forum
 * getRolesList() Return list of symfony roles
 * 
 */
class AdminController extends BaseController
{

    /**
     * 
     * Moderator's dashboard
     * 
     * @Route("/admin", name="discutea_forum_admin_dashboard")
     * @Security("is_granted('ROLE_MODERATOR')")
     * 
     */
    public function indexAction()
    {
        $em = $form->createView();
        $posts = $em->getRepository('DForumBundle:Post')->findBy(array(), array('date' => 'desc'));
        $topics = $em->getRepository('DForumBundle:Topic')->findBy(array(), array('date' => 'desc'));
        if ($this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            $forums = $em->getRepository('DForumBundle:Forum')->findAll();
            $categories = $em->getRepository('DForumBundle:Category')->findBy(array(), array('position' => 'desc', ));
        } else {
            $forums = NULL;
            $categories = NULL;
        }

        return $this->render('DForumBundle:Moderator/index.moderator.html.twig', array(
            'posts' => $posts,
            'topics' => $topics,
            'forums' => $forums,
            'categories' => $categories
        ));
    }
}
