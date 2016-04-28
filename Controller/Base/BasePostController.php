<?php

namespace Discutea\DForumBundle\Controller\Base;

use Discutea\DForumBundle\Controller\Base\BaseController;

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
    /*
     * @var object $post Discutea\DForumBundle\Entity\Post
     */
    protected $post;

    /**
     * Generate post form or return Null if not authorised
     * 
     * @param objet $request  Symfony\Component\HttpFoundation\Request
     * @param objet $topic  Discutea\DForumBundle\Entity\Topic
     * 
     * @return NULL|object Symfony\Component\Form\Form
     */
    protected function generatePostForm(Request $request, Topic $topic) {


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
     * @param objet $posts Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination Listing posts in pagination
     * 
     * @return object Symfony\Component\HttpFoundation\RedirectResponse redirecting in last post
     */
    protected function redirectAfterPost(SlidingPagination $posts) {

        $totalPosts = $posts->getTotalItemCount() + 1;
        $nbrPerPage = $posts->getItemNumberPerPage();
        $pagesCount =  ceil( $totalPosts / $nbrPerPage );

        $url = $this->generateUrl('discutea_forum_post', array(
            'slug' => $this->post->getTopic()->getSlug(),
            'p'    => $pagesCount
        ));  
        
        $redirection = ''.$url.'#post'.$this->post->getId().'';

        return $this->redirect($redirection);
    }

    /**
     * Check if last posts page for display form
     * 
     * @param objet $posts Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination Listing posts in pagination
     * @param objet $request Symfony\Component\HttpFoundation\Request
     * @param object $form Symfony\Component\Form\Form
     * 
     * @return NULL|object Symfony\Component\Form\FormView
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
     * @param objet $request Symfony\Component\HttpFoundation\Request
     * @param object $form Symfony\Component\Form\Form
     * @param objet $post Discutea\DForumBundle\Entity\Post Listing posts in pagination
     * 
     * @return false|object Discutea\DForumBundle\Entity\Post
     */
    protected function getPreview(Request $request, Form $form, Post $post) {
        
        if ( $form->get('preview')->isClicked() ) {
            $request->getSession()->getFlashBag()->add('warning', $this->getTranslator()->trans('discutea.forum.warning.preview'));
            return $post; 
        }
            
        return false; 
    }

    /**
     * Find and inject post quoted if post is not null
     *
     * @param integer $quote the ID of the post that is quote
     * 
     * @return NULL|string content post in quoted formated for bbcode
     */
    protected function addQuote($quote) {
        $post = $this->getEm()->getRepository('DForumBundle:Post')->find($quote);
        if ($post === NULL) {
            return NULL;
        }
        
        return '[quote='.$post->getPoster()->getUsername().']'.$post->getContent().'[/quote]';
    }
    
}
