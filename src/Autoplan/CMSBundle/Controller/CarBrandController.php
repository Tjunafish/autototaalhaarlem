<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\CarBrandType;
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
 * CarBrand controller.
 *
 * @Route("/automerken")
 */
class CarBrandController extends Controller
{

    /**
     * Lists all CarBrand entities.
     *
     * @Route("/", name="admin_car_brand")
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
            'brands' => $oEm->getRepository('AutoplanDBBundle:CarBrand')->findBy(array(), array('title' => 'ASC'))
        );
    }

    /**
     * Displays a form to edit an existing Text entity.
     *
     * @Route("/{id}/edit", name="admin_car_brand_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:CarBrand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CarBrand entity.');
        }

        $editForm = $this->createForm(new CarBrandType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_car_brand'));
            }
        }

        return array(
            'menu' => 'cars',
            'brand'      => $entity,
            'form'   => $editForm->createView(),
        );
    }
}
