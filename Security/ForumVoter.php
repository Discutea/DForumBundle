<?php
namespace Discutea\DForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;
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
    const CANDISPLAYFORUM = 'CanDisplayForum';
    
    /**
     *
     * @var object Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface
     * 
     */
    private $decisionManager;
    
    private $request;

    /**
     * 
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, RequestStack $request)
    {
        $this->decisionManager = $decisionManager;
        $this->request = $request->getCurrentRequest();
    }
    
    protected function supports($attribute, $forum)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::CANREADFORUM, self::CANDISPLAYFORUM))) {
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

        switch($attribute) {
            case self::CANREADFORUM:
                return $this->canReadForum($forum, $token);
            case self::CANDISPLAYFORUM:
                return $this->canDisplayForum($forum);
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

    public function canDisplayForum(Forum $forum)
    {
        $locale = $this->request->getLocale();
        $translation = $forum->translate($locale);

        if ( ( null !== $translation->getLocale() ) && ( $locale == $translation->getLocale() ) ) {
            return true;
        }

        return false;
    }
}
