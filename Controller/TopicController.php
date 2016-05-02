<?php
namespace Discutea\DForumBundle\Controller;


use Discutea\DForumBundle\Controller\Base\BaseTopicController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Discutea\DForumBundle\Form\Type\TopicEditType;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Topic;
use Symfony\Component\HttpFoundation\Request;

/**
 * TopicController 
 * 
 * This class contains useful methods for the proper functioning of the topic controller and not method actions.
 * This class extends BaseController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TopicController extends BaseTopicController
{

    /**
     * infos:  Display the topics of a forum
     * 
     * @Route("/cat/{slug}", name="forum_topic")
     * ParamConverter("forum")
     * @ParamConverter("forum", class="DForumBundle:Forum", options={
     *    "repository_method" = "findByTranslatedSlug",
     *    "mapping": {"slug": "slug", "_locale": "locale"},
     *    "map_method_signature" = true
     * })
     * @Security("is_granted('CanReadForum', forum)")
     * 
     */
    public function topicAction(Request $request, Forum $forum)
    {

        $topics = $forum->getTopicsByLocale( array( $request->getLocale() ) ); 
      
        $pagination = $this->get('discutea.forum.pagin')->pagignate('topics', $topics);
        
        if (($form = $this->generateTopicForm($request->getLocale(), $forum)) !== NULL) {
            if ($form->handleRequest($request)->isValid()) {
                $content = $form->get('content')->getData();
                $post = $this->createPost($content, $this->topic);

                $this->getEm()->persist($this->topic);
                $this->getEm()->persist($post);
                $this->getEm()->flush();

                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.topic.create'));
                return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $this->topic->getSlug())));
            }

            $form = $form->createView();
        }

        return $this->render('DForumBundle:topic.html.twig', array(
            'forum' => $forum,
            'pagination' => $pagination,
            'form' => $form
        ));
    }

    /**
     * @Route("/topic/delete/{id}", name="discutea_forum_topic_delete")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function deleteAction(Request $request, Topic $topic)
    {
        $forumSlug = $topic->getForum()->getSlug();
        $this->getEm()->remove($topic);
        $this->getEm()->flush();
        $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('Discutea.forum.topic.delete'));
        return $this->redirect($this->generateUrl('forum_topic', array('slug' => $forumSlug)));       
 
    }

    /**
     * @Route("/topic/edit/{id}", name="discutea_forum_topic_edit")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_MODERATOR')")
     * 
     */
    public function editAction(Request $request, Topic $topic)
    {
        $form = $this->createForm(TopicEditType::class, $topic);
        
        if ($form->handleRequest($request)->isValid()) {
            $forumSlug = $topic->getForum()->getSlug();
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.topic.edit'));
            return $this->redirect($this->generateUrl('forum_topic', array('slug' => $forumSlug)));
        }
         
        return $this->render('DForumBundle:Form/topic_edit.html.twig', array(
            'form'  => $form->createView()
        ));
    }

}
