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
     * @Route("/label/solved/{slug}", name="discutea_label_solved")
     * @ParamConverter("topic")
     * @Security("is_granted('CanEditTopic', topic)")
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function solvedAction(Request $request, Topic $topic)
    {        
        $em = $this->getEm();
        if ( $topic->getResolved() !== false ) {
            $topic->setResolved(false);
            
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.solved'));
        } else {
            $topic->setResolved(true);
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.solved'));
        }

        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }

    /**
     * @Route("/label/pinned/{slug}", name="discutea_label_pinned")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function pinnedAction(Request $request, Topic $topic)
    {
        $em = $this->getEm();
        if ( $topic->getPinned() !== false ) {
            $topic->setPinned(false);
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.pinned'));
        } else {
            $topic->setPinned(true);
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.pinned'));
        }
 
        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }

    /**
     * @Route("/label/closed/{slug}", name="discutea_label_closed")
     * @ParamConverter("topic")
     * @Security("has_role('ROLE_MODERATOR') and  is_granted('CanEditTopic', topic)")
     *
     * @param Request $request
     * @param Topic $topic
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function closedAction(Request $request, Topic $topic)
    {
        $em = $this->getEm();
        if ($topic->getClosed() !== false ) {
            $topic->setClosed(false);
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.unmark.closed'));
        } else {
            $topic->setClosed(true);
            $em->persist($topic);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('discutea.forum.label.mark.closed'));
        }
 
        return $this->redirect($this->generateUrl('discutea_forum_post', array('slug' => $topic->getSlug())));
    }
}
