<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\FAQType;
use Autoplan\DBBundle\Entity\FAQ;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * FAQ controller.
 *
 * @Route("/vragen")
 */
class FAQController extends Controller
{

    /**
     * Lists all FAQ entities.
     *
     * @Route("/", name="admin_faq")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oRequest = $this->getRequest();


        return array(
            'menu' => 'text',
            'faqs' => $oEm->getRepository('AutoplanDBBundle:FAQ')->findBy(array(), array('sequence' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new FAQ entity.
     *
     * @Route("/new", name="admin_faq_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $entity = new FAQ();
        $form   = $this->createForm(new FAQType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {

                $oHighest = $oEm->getRepository('AutoplanDBBundle:FAQ')->findOneBy(array(), array('sequence' => 'desc'));
                if(null===$oHighest) {
                    $entity->setSequence(0);
                } else {
                    $entity->setSequence($oHighest->getSequence()+1);
                }

                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_faq'));
            }
        }

        return array(
            'menu'   => 'text',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FAQ entity.
     *
     * @Route("/{id}/edit", name="admin_faq_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $editForm = $this->createForm(new FAQType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_faq'));
            }
        }

        return array(
            'menu'   => 'text',
            'faq'    => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a FAQ entity.
     *
     * @Route("/delete/{id}", name="admin_faq_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        $questions = $oEm->getRepository('AutoplanDBBundle:FAQ')->findBy(array(), array('sequence' => 'ASC'));
        $iSequence = 0;
        foreach($questions as $question) {
            $question->setSequence($iSequence);
            $iSequence++;
        }
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_faq'));
    }

    /**
     * Moves a FAQ entity.
     *
     * @Route("/up/{id}", name="admin_faq_up")
     */
    public function upAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        if($entity->getSequence() > 0) {
            $oOther = $oEm->getRepository('AutoplanDBBundle:FAQ')->findOneBy(array('sequence' => $entity->getSequence()-1));

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()-1);


            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_faq'));
    }

    /**
     * Moves a FAQ entity.
     *
     * @Route("/down/{id}", name="admin_faq_down")
     */
    public function downAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:FAQ')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FAQ entity.');
        }

        $oOther = $oEm->getRepository('AutoplanDBBundle:FAQ')->findOneBy(array('sequence' => $entity->getSequence()+1));
        if(null!==$oOther) {

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()+1);
            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_faq'));
    }
}
