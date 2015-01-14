<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\NewsletterSubscriptionType;
use Autoplan\DBBundle\Entity\NewsletterSubscription;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * NewsletterSubscription controller.
 *
 * @Route("/newsletter_subscribers")
 */
class NewsletterSubscriptionController extends Controller
{

    /**
     * Lists all TEXT entities.
     *
     * @Route("/", name="admin_newsletter_subscribers")
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
            'entities' => $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findBy(array(), array('name' => 'ASC'))
        );
    }

    /**
     * Displays a form to create a new TEXT entity.
     *
     * @Route("/new", name="admin_newsletter_subscribe_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();

        $entity = new NewsletterSubscription();
        $form   = $this->createForm(new NewsletterSubscriptionType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_subscribers'));
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
     * @Route("/{id}/edit", name="admin_newsletter_subscribe_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterSubscription entity.');
        }

        $editForm = $this->createForm(new NewsletterSubscriptionType(), $entity);


        if($oRequest->isMethod("POST")) {
            $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_subscribers'));
            }
        }

        return array(
            'menu'   => 'newsletters',
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Displays a form to upload a CSV file to import the subscribers
     *
     * @Route("/import", name="admin_newsletter_subscribers_import")
     * @Template()
     */
    public function importAction()
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        set_time_limit(300);

        if($oRequest->isMethod("POST")) {
            $files = $oRequest->files;

            foreach ($files as $file) {

                $url = $file->getPathName();

                if (($handle = fopen($file->getPathName(), 'r')) !== false) {
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                        if (count($data) !== 2) {
                            throw $this->createNotFoundException('Data from the CSV is wrong');
                        }

                        list($name, $email) = $data;

                        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findOneByEmail($email);

                        if (!$entity) {
                            $entity = new NewsletterSubscription();
                        }
                        $entity->setName($name);
                        $entity->setEmail($email);
                        $entity->setActive(1);

                        $oEm->persist($entity);
                        $oEm->flush();
                    }
                    fclose($handle);
                }

                return $this->redirect($this->generateUrl('admin_newsletter_subscribers'));
            }
        }

        return array(
            'menu'   => 'newsletters'
        );
    }

    /**
     * Export subscribers to CSV
     *
     * @Route("/export", name="admin_newsletter_subscribers_export")
     * @Template()
     */
    public function exportAction()
    {

        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();

            // The getExportQuery method returns a query that is used to retrieve
            // all the objects (lines of your csv file) you need. The iterate method
            // is used to limit the memory consumption
            $results = $em->getRepository('AutoplanDBBundle:NewsletterSubscription')
                ->createQueryBuilder('j')
                ->getQuery()
                ->iterate();
            $handle = fopen('php://output', 'r+');

            while (false !== ($row = $results->next())) {
                // add a line in the csv file. You need to implement a toArray() method
                // to transform your object into an array
                fputcsv($handle, $row[0]->toArray());
                // used to limit the memory consumption
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }


    /**
     * Deletes a Page entity.
     *
     * @Route("/delete/{id}", name="admin_newsletter_subscribe_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterSubscription entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_newsletter_subscribers'));
    }
}
