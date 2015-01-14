<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\NewsletterGroupType;
use Autoplan\DBBundle\Entity\NewsletterGroup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * NewsletterGroup controller.
 *
 * @Route("/newsletter_groups")
 */
class NewsletterGroupController extends Controller
{

    /**
     * Lists all TEXT entities.
     *
     * @Route("/", name="admin_newsletter_groups")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oRequest = $this->getRequest();

        return array(
            'menu' => 'newsletters',
            'entities' => $oEm->getRepository('AutoplanDBBundle:NewsletterGroup')->findBy(array(), array('name' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new TEXT entity.
     *
     * @Route("/new", name="admin_newsletter_group_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();

        $entity = new NewsletterGroup();
        $form   = $this->createForm(new NewsletterGroupType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_groups'));
            }
        }

        return array(
            'menu' => 'newsletters',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Text entity.
     *
     * @Route("/{id}/edit", name="admin_newsletter_group_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
        }

        $editForm = $this->createForm(new NewsletterGroupType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_groups'));
            }
        }

        return array(
            'menu'   => 'newsletters',
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Page entity.
     *
     * @Route("/delete/{id}", name="admin_newsletter_group_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterGroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterGroup entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_newsletter_groups'));
    }
}
