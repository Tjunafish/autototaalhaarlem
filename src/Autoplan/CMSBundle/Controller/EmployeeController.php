<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\EmployeeType;
use Autoplan\DBBundle\Entity\Employee;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Employee controller.
 *
 * @Route("/medewerkers")
 */
class EmployeeController extends Controller
{

    /**
     * Lists all Employee entities.
     *
     * @Route("/", name="admin_employee")
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
            'employees' => $oEm->getRepository('AutoplanDBBundle:Employee')->findBy(array(), array('sequence' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new Employee entity.
     *
     * @Route("/new", name="admin_employee_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $entity = new Employee();
        $form   = $this->createForm(new EmployeeType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {

                $oHighest = $oEm->getRepository('AutoplanDBBundle:Employee')->findOneBy(array(), array('sequence' => 'desc'));
                if(null===$oHighest) {
                    $entity->setSequence(0);
                } else {
                    $entity->setSequence($oHighest->getSequence()+1);
                }

                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_employee'));
            }
        }

        return array(
            'menu'   => 'text',
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Employee entity.
     *
     * @Route("/{id}/edit", name="admin_employee_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }

        $editForm = $this->createForm(new EmployeeType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_employee'));
            }
        }

        return array(
            'menu'   => 'text',
            'faq'    => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Employee entity.
     *
     * @Route("/delete/{id}", name="admin_employee_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        $questions = $oEm->getRepository('AutoplanDBBundle:Employee')->findBy(array(), array('sequence' => 'ASC'));
        $iSequence = 0;
        foreach($questions as $question) {
            $question->setSequence($iSequence);
            $iSequence++;
        }
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_employee'));
    }

    /**
     * Moves a Employee entity.
     *
     * @Route("/up/{id}", name="admin_employee_up")
     */
    public function upAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }

        if($entity->getSequence() > 0) {
            $oOther = $oEm->getRepository('AutoplanDBBundle:Employee')->findOneBy(array('sequence' => $entity->getSequence()-1));

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()-1);


            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_employee'));
    }

    /**
     * Moves a Employee entity.
     *
     * @Route("/down/{id}", name="admin_employee_down")
     */
    public function downAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Employee')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }

        $oOther = $oEm->getRepository('AutoplanDBBundle:Employee')->findOneBy(array('sequence' => $entity->getSequence()+1));
        if(null!==$oOther) {

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()+1);
            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_employee'));
    }
}
