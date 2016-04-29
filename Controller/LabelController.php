<?php
namespace Discutea\DForumBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Discutea\DForumBundle\Entity\Topic;

/**
 * @author David Verdier <contact@discutea.com>
 * 
 * Class LabelController
 *
 */
class LabelController extends BaseController
{
    
    /**
     * 
     * @Route("/label/solved/{slug}", name="discutea_label_solved")
     * @ParamConverter("topic")
     * @Security("is_granted('CanEditTopic', topic)")
     *
     */
    public function solvedAction(Request $request, Topic $topic)
    {        
        if ( $topic->getResolved() ) {
            $topic->setResolved(NULL);
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.solved'));
        } else {
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $topic->setResolved(true);
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.solved'));
        }
                
        
 
        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }

    /**
     * 
     * @Route("/label/pinned/{slug}", name="discutea_label_pinned")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function pinnedAction(Request $request, Topic $topic)
    {        
        if ( $topic->getPinned() ) {
            $topic->setPinned(NULL);
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.pinned'));
        } else {
            $topic->setPinned(true);
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.pinned'));
        }
 
        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }

    /**
     * 
     * @Route("/label/closed/{slug}", name="discutea_label_closed")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_MODERATOR')")
     *
     */
    public function closedAction(Request $request, Topic $topic)
    {        
        if ($topic->getClosed() ) {
            $topic->setClosed(NULL);
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.closed'));
        } else {
            $topic->setClosed(true);
            $this->getEm()->persist($topic);
            $this->getEm()->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.closed'));
        }
 
        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }
}