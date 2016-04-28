<?php
namespace Discutea\DForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Discutea\DForumBundle\Entity\Topic;

/**
 * Topic Voter 
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class TopicVoter extends Voter
{

    const CANREADTOPIC = 'CanReadTopic';
    const CANREPLYTOPIC = 'CanReplyTopic';
    const CANEDITTOPIC = 'CanEditTopic';
    
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

    protected function supports($attribute, $topic)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREADTOPIC, self::CANREPLYTOPIC, self::CANEDITTOPIC))) {
            return false;
        }

        // only vote on Forum objects inside this voter
        if (!$topic instanceof Topic) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $topic, TokenInterface $token)
    {

        switch($attribute) {
            case self::CANREADTOPIC:
                return $this->canReadTopic($topic, $token);
            case self::CANREPLYTOPIC:
                return $this->canReplyTopic($topic, $token);
            case self::CANEDITTOPIC:
                return $this->canEditTopic($topic, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to read topic
     * 
     * @param Topic $topic
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReadTopic(Topic $topic, TokenInterface $token)
    {
        $category = $topic->getForum()->getCategory();
        $roleToRead = $category->getReadAuthorisedRoles();
        
        if ( ($roleToRead === NULL) || ($this->decisionManager->decide($token, array($roleToRead))) ) {
            return true;
        }

        return false;
    }

    /**
     * 
     * Control if user's is autorized to Reply topic
     * 
     * @param Topic $topic
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReplyTopic(Topic $topic, TokenInterface $token)
    {

        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }
        
        if (!$this->decisionManager->decide($token, array('IS_AUTHENTICATED_REMEMBERED'))) {
            return false;
        }
        
        if ( $topic->getClosed() ) {
            return false;
        }
 
        return true;
    }

    /**
     * 
     * Control if user's is autorized to Edit topic
     * 
     * @param Topic $topic
     * @param TokenInterface $token
     * @return boolean
     */
    public function canEditTopic(Topic $topic, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array('ROLE_MODERATOR'))) {
            return true;
        }
        
        if (!$this->decisionManager->decide($token, array('IS_AUTHENTICATED_REMEMBERED'))) {
            return false;
        }
        
        if ( $topic->getUser() !== $token->getUser() ) {
            return false;
        }

        return true;
    }
}
