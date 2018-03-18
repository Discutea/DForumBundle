<?php

namespace Discutea\DForumBundle\Controller\Base;

use Discutea\DForumBundle\Entity\Post;
use Discutea\DForumBundle\Form\Type\PostType;
use Discutea\DForumBundle\Entity\Topic;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\Form\Form;

/**
 * BasePostController 
 * 
 * This class contains useful methods for the proper functioning of the post controller and not method actions.
 * This class extends BaseController.
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   protected
 */
class BasePostController extends BaseController
{
    /**
     * @var object $post Discutea\DForumBundle\Entity\Post
     */
    protected $post;

    /**
     * Generate post form or return Null if not authorised
     *
     * @param Request $request
     * @param Topic $topic
     * @return null|\Symfony\Component\Form\FormInterface
     */
    protected function generatePostForm(Request $request, Topic $topic)
    {
        if  ( $this->isGranted('CanReplyTopic', $topic) ) {
            $this->post = new Post();
            $this->post->setTopic($topic);
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $this->post->setPoster($user);

            if ( ($quote = $request->query->getInt('quote')) ) {
                $this->post->setContent( $this->addQuote($quote) );
            }
            
            return $this->createForm(PostType::class, $this->post, array( 
                'preview' => $this->container->getParameter('discutea_forum.preview')
            ));
        }
        
        return NULL;
    }

    /**
     * After post redirect in post
     *
     * @param SlidingPagination $posts
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectAfterPost(SlidingPagination $posts) {

        $totalPosts = $posts->getTotalItemCount() + 1;
        $nbrPerPage = $posts->getItemNumberPerPage();
        $pagesCount =  ceil( $totalPosts / $nbrPerPage );
        $paramPage  = $this->container->getParameter('discutea_forum.pagination')["page_name"];

        $url = $this->generateUrl('discutea_forum_post', array(
            'slug'     => $this->post->getTopic()->getSlug(),
            $paramPage => $pagesCount
        ));  
        
        $redirection = ''.$url.'#post'.$this->post->getId().'';

        return $this->redirect($redirection);
    }

    /**
     * Check if last posts page for display form
     *
     * @param SlidingPagination $posts
     * @param Request $request
     * @param Form $form
     * @return null|\Symfony\Component\Form\FormView
     */
    protected function autorizedPostForm(SlidingPagination $posts, Request $request, Form $form) {
        if ($posts->getPageCount() == $request->query->get( $this->container->getParameter('discutea_forum.pagination.pagename') , 1)) {
            return $form->createView();
        }
        
        return NULL;
    }

    /**
     * Check if preview is clicked
     *
     * @param Request $request
     * @param Form $form
     * @param Post $post
     * @return bool|Post
     */
    protected function getPreview(Request $request, Form $form, Post $post) {
       if ( false === $this->container->getParameter('discutea_forum.preview') ) {
            return false;
        }
        if ( $form->get('preview')->isClicked() ) {
            $request->getSession()->getFlashBag()->add('warning', $this->getTranslator()->trans('discutea.forum.warning.preview'));
            return $post; 
        }
            
        return false; 
    }

    /**
     * Find and inject post quoted if post is not null
     *
     * @param $quote
     * @return null|string
     */
    protected function addQuote($quote) {
        $post = $this->getEm()->getRepository(Post::class)->find($quote);
        if ($post === NULL) {
            return NULL;
        }
        
        return '[quote='.$post->getPoster()->getUsername().']'.$post->getContent().'[/quote]';
    }
}
