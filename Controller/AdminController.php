<?php

namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Post;
use Discutea\DForumBundle\Entity\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @author David Verdier <contact@discutea.com>
 * 
 * Class AdminController
 * 
 * METHODS LIST:
 * indexAction() return discutea_forum_admin_dashboard
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
     * @Route("/admin", name="discutea_forum_admin_dashboard")
     * @Security("is_granted('ROLE_MODERATOR')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getEm();
        $posts = $em->getRepository(Post::class)->findBy(array(), array('date' => 'desc'));
        $topics = $em->getRepository(Topic::class)->findBy(array(), array('date' => 'desc'));
        if ($this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            $forums = $em->getRepository(Forum::class)->findAll();
            $categories = $em->getRepository(Category::class)->findBy(array(), array('position' => 'desc', ));
        } else {
            $forums = NULL;
            $categories = NULL;
        }

        return $this->render('@DForum/Moderator/index.moderator.html.twig', array(
            'posts' => $posts,
            'topics' => $topics,
            'forums' => $forums,
            'categories' => $categories
        ));
    }
}
