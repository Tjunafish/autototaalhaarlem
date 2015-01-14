<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\PageType;
use Autoplan\DBBundle\Entity\Page;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller.
 *
 * @Route("/paginas")
 */
class PageController extends Controller
{

    /**
     * Lists all TEXT entities.
     *
     * @Route("/", name="admin_page")
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
            'pages' => $oEm->getRepository('AutoplanDBBundle:Page')->findBy(array(), array('title' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new TEXT entity.
     *
     * @Route("/new", name="admin_page_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();

        $entity = new Page();
        $form   = $this->createForm(new PageType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_page'));
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
     * @Route("/{id}/edit", name="admin_page_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createForm(new PageType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_page'));
            }
        }

        return array(
            'menu' => 'text',
            'page'      => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Page entity.
     *
     * @Route("/delete/{id}", name="admin_page_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Page')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_page'));
    }
}
