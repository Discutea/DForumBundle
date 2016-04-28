<?php
namespace Discutea\DForumBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Discutea\DForumBundle\Entity\Category;

/**
 * Category Voter 
 * 
 * @package  DForumBundle
 * @author   David Verdier <contact@discutea.com>
 * @access   public
 */
class CategoryVoter extends Voter
{
    
    const CANREADCATEGORY = 'CanReadCategory';

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
        if (!in_array($attribute, array(self::CANREADCATEGORY))) {
            return false;
        }

        // only vote on Forum objects inside this voter
        if (!$forum instanceof Category) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $category, TokenInterface $token)
    {

        if ($attribute === self::CANREADCATEGORY) {
            return $this->canReadCategory($category, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * 
     * Control if user's is autorized to Read category
     * 
     * @param Category $category
     * @param TokenInterface $token
     * @return boolean
     */
    public function canReadCategory(Category $category, TokenInterface $token)
    {
        $roleToRead = $category->getReadAuthorisedRoles();

        if ( ($roleToRead === NULL) || ($this->decisionManager->decide($token, array($roleToRead))) ) {
            return true;
        }

        return false;
    }

}
