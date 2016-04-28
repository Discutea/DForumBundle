<?php
namespace Discutea\DForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Discutea\DForumBundle\Entity\Post;

/**
 * Post Voter 
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class PostVoter extends Voter
{
    
    const CANREPLYPOST = 'CanReplyPost';
    const CANEDITPOST = 'CanEditPost';

    /**
     *
     * @var object Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface
     * 
     */
    private $decisionManager;

    /**
     * 
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    
    protected function supports($attribute, $post)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREPLYPOST, self::CANEDITPOST))) {
            return false;
        }

        if (!$post instanceof Post) {
            return false;
        }
        
        return true;
    }

    protected function voteOnAttribute($attribute, $post, TokenInterface $token)
    {
        switch($attribute) {
            case self::CANREPLYPOST:
                return $this->canReplyPost($post, $token);
            case self::CANEDITPOST:
                return $this->canEditPost($post, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to Reply post
     * 
     * @param Post $post
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReplyPost(Post $post, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }
        
        if (!$this->decisionManager->decide($token, array('IS_AUTHENTICATED_REMEMBERED'))) {
            return false;
        }
        
        $topic = $post->getTopic();
        
        if ( $topic->getClosed() ) {
            return false;
        }

        if ( $topic->getPinned() ) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Control if user's is autorized to Edit topic
     * 
     * @param Post $post
     * @param TokenInterface $token
     * @return boolean
     */
    public function canEditPost(Post $post, TokenInterface $token)
    {
       
        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }
        
        if (!$this->decisionManager->decide($token, array('IS_AUTHENTICATED_REMEMBERED'))) {
            return false;
        }
        
        if ( $post->getPoster() !== $token->getUser() ) {
            return false;
        }

        return true;
    }
}
