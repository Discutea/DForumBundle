<?php
namespace Discutea\DForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


use Discutea\DForumBundle\Entity\Forum;

/**
 * Forum Voter 
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class ForumVoter extends Voter
{
    
    const CANREADFORUM = 'CanReadForum';

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
    
    protected function supports($attribute, $forum)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREADFORUM))) {
            return false;
        }

        // only vote on Forum objects inside this voter
        if (!$forum instanceof Forum) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $forum, TokenInterface $token)
    {
        if ($attribute === self::CANREADFORUM) {
            return $this->canReadForum($forum, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to Read forum
     * 
     * @param Forum $forum
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReadForum(Forum $forum, TokenInterface $token)
    {

        $category = $forum->getCategory();
        $roleToRead = $category->getReadAuthorisedRoles();
        
        if ( ($roleToRead === NULL) || ($this->decisionManager->decide($token, array($roleToRead))) ) {
            return true;
        }

        return false;
    }
}
