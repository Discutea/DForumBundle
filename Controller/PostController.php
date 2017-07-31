<?php
namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BasePostController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Discutea\DForumBundle\Entity\Post;
use Discutea\DForumBundle\Form\Type\PostType;
use Discutea\DForumBundle\Entity\Topic;
use Symfony\Component\HttpFoundation\Request;

/**
 * PostController 
 * 
 * This class contains actions methods for post.
 * This class extends BasePostController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class PostController extends BasePostController
{

    /**
     * 
     * @Route("topic/{slug}", name="discutea_forum_post")
     * @ParamConverter("topic")
     * @Security("is_granted('CanReadTopic', topic)")
     *
     * @param objet $request Symfony\Component\HttpFoundation\Request
     * @param objet $topic Discutea\DForumBundle\Entity\Topic
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting in last post
     * @return objet Symfony\Component\HttpFoundation\Response
     */
    public function postAction(Request $request, Topic $topic)
    {
        $preview = false;
        
        $posts = $this->getPaginator()->pagignate('posts', $topic->getPosts());

        if (( $form = $this->generatePostForm($request, $topic) ) !== NULL) {
            if ($form->handleRequest($request)->isValid()) {
                if ( !$preview = $this->getPreview($request, $form, $this->post) ) {
                    $em = $this->getEm();
                    $em->persist($this->post);
                    $em->flush();
                    $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.post.create'));
                    return $this->redirectAfterPost($posts);
                }
            }
            $form = $this->autorizedPostForm($posts, $request, $form);
        }
        
        return $this->render('DForumBundle::post.html.twig', array(
            'topic' => $topic,
            'posts' => $posts,
            'form'  => $form,
            'postpreview' => $preview
        ));
    }
    
    /**
     * 
     * Delete a post and redirection in post page or topic page after delete.
     * 
     * @Route("post/delete/{id}", name="discutea_forum_post_delete")
     * @ParamConverter("post")
     * @Security("has_role('ROLE_MODERATOR') and is_granted('CanEditPost', post)")
     *
     * @param objet $post Discutea\DForumBundle\Entity\Post
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Post $post)
    {
        $topic = $post->getTopic();

        if ($topic->getPosts()->first() === $post)
        {
            $em = $this->getEm();
            $em->remove($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.topic.delete'));
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.post.deleteall'));
            $redirect = $this->generateUrl('discutea_forum_topic', array('slug' => $topic->getForum()->getSlug()));
        } else {
            $em = $this->getEm();
            $em->remove($post);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.post.delete'));
            $redirect = $this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())); 
        }
        return $this->redirect($redirect);
    }

    /**
     * 
     * @Route("post/edit/{id}", name="discutea_forum_post_edit")
     * @ParamConverter("post")
     * @Security("is_granted('CanEditPost', post)")
     * 
     * @param objet $request Symfony\Component\HttpFoundation\Request
     * @param objet $post Discutea\DForumBundle\Entity\Post
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse
     * @return objet Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Post $post)
    {
        $preview = false;

        $form = $this->createForm(PostType::class, $post, array( 
            'preview' => $this->container->getParameter('discutea_forum.preview')
        ));

        if ($form->handleRequest($request)->isSubmitted()) {
            if ( !$preview = $this->getPreview($request, $form, $post) ) {
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $post->setUpdated(new \DateTime());
                $post->setUpdatedBy($user);
                $this->getEm()->flush();
                $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.post.edit'));
                return $this->redirect( $this->generateUrl('discutea_forum_post', array('slug' => $post->getTopic()->getSlug())) ); 
            }
        }

        return $this->render('DForumBundle::Post/edit_post.html.twig', array(
            'form'  => $form->createView(),
            'post'  => $post,
            'topic' => $post->getTopic(),
            'postpreview' => $preview
        ));

    }

}
