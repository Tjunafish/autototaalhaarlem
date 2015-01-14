<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\BannerType;
use Autoplan\CMSBundle\Form\EmployeeType;
use Autoplan\DBBundle\Entity\Banner;
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
 * @Route("/banners")
 */
class BannerController extends Controller
{

    /**
     * Lists all Employee entities.
     *
     * @Route("/", name="admin_banner")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oRequest = $this->getRequest();

        $iPos = $oRequest->get('pos', 0);


        return array(
            'menu' => 'banner',
            'pos' => $iPos,
            'banners' => $oEm->getRepository('AutoplanDBBundle:Banner')->findBy(array('pos' => $iPos), array('sequence' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new Banner entity.
     *
     * @Route("/{pos}/new", name="admin_banner_new")
     * @Template()
     */
    public function newAction($pos)
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $entity = new Banner();
        $form   = $this->createForm(new BannerType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {

                $oHighest = $oEm->getRepository('AutoplanDBBundle:Banner')->findOneBy(array('pos'=>$pos), array('sequence' => 'desc'));
                if(null===$oHighest) {
                    $entity->setSequence(0);
                } else {
                    $entity->setSequence($oHighest->getSequence()+1);
                }

                $entity->setPos($pos);

                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_banner', array('pos' => $pos)));
            }
        }

        return array(
            'menu'   => 'banner',
            'entity' => $entity,
            'form'   => $form->createView(),
            'pos' => $pos,
        );
    }

    /**
     * Displays a form to edit an existing Banner entity.
     *
     * @Route("/{id}/edit", name="admin_banner_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $editForm = $this->createForm(new BannerType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_banner', array('pos' => $entity->getPos())));
            }
        }

        return array(
            'menu'   => 'banner',
            'banner'    => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Employee entity.
     *
     * @Route("/delete/{id}", name="admin_banner_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        /** @var Banner $entity */
        $entity = $oEm->getRepository('AutoplanDBBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Employee entity.');
        }
        $iOldPos = $entity->getPos();

        $oEm->remove($entity);
        $oEm->flush();

        $banners = $oEm->getRepository('AutoplanDBBundle:Banner')->findBy(array('pos' => $iOldPos), array('sequence' => 'ASC'));
        $iSequence = 0;
        foreach($banners as $banner) {
            /** @var Banner $banner */
            $banner->setSequence($iSequence);
            $iSequence++;
        }
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_banner', array('pos' => $iOldPos)));
    }

    /**
     * Moves a Employee entity.
     *
     * @Route("/up/{id}", name="admin_banner_up")
     */
    public function upAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        /** @var Banner $entity */
        /** @var Banner $oOther */
        $entity = $oEm->getRepository('AutoplanDBBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        if($entity->getSequence() > 0) {
            $oOther = $oEm->getRepository('AutoplanDBBundle:Banner')->findOneBy(array('pos' => $entity->getPos(), 'sequence' => $entity->getSequence()-1));

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()-1);


            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_banner', array('pos' => $entity->getPos())));
    }

    /**
     * Moves a Employee entity.
     *
     * @Route("/down/{id}", name="admin_banner_down")
     */
    public function downAction(Request $request, $id)
    {
        /** @var Banner $entity */
        /** @var Banner $oOther */

        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $oOther = $oEm->getRepository('AutoplanDBBundle:Banner')->findOneBy(array('pos' => $entity->getPos(), 'sequence' => $entity->getSequence()+1));
        if(null!==$oOther) {

            $oOther->setSequence($entity->getSequence());
            $entity->setSequence($entity->getSequence()+1);
            $oEm->flush();
        }


        return $this->redirect($this->generateUrl('admin_banner', array('pos' => $entity->getPos())));
    }
}
