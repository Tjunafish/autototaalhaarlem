<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\TextType;
use Autoplan\DBBundle\Entity\Text;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Text controller.
 *
 * @Route("/teksten")
 */
class TextController extends Controller
{

    /**
     * Lists all TEXT entities.
     *
     * @Route("/", name="admin_text")
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
            'texts' => $oEm->getRepository('AutoplanDBBundle:Text')->findBy(array(), array('title' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new TEXT entity.
     *
     * @Route("/new", name="admin_text_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();

        $entity = new Text();
        $form   = $this->createForm(new TextType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_text'));
            }
        }

        return array(
            'menu' => 'text',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Text entity.
     *
     * @Route("/{id}/edit", name="admin_text_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Text')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Text entity.');
        }

        $editForm = $this->createForm(new TextType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_text'));
            }
        }

        return array(
            'menu' => 'text',
            'text'      => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Text entity.
     *
     * @Route("/delete/{id}", name="admin_text_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Text')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Text entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_text'));
    }
}
