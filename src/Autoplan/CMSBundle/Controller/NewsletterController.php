<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\NewsletterType;
use Autoplan\DBBundle\Entity\Newsletter;
use Autoplan\DBBundle\Entity\NewsletterGroup;
use Autoplan\DBBundle\Entity\NewsletterSubscription;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;


use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;

/**
 * NewsletterSubscription controller.
 *
 * @Route("/newsletters")
 */
class NewsletterController extends Controller
{

    /**
     * Lists all TEXT entities.
     *
     * @Route("/", name="admin_newsletters")
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
            'entities' => $oEm->getRepository('AutoplanDBBundle:Newsletter')->findBy(array(), array('created' => 'DESC'))
        );
    }

    /**
     * Displays a form to create a new TEXT entity.
     *
     * @Route("/new", name="admin_newsletter_new")
     * @Template()
     */
    public function newAction()
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();

        $entity = new Newsletter();
        $form   = $this->createForm(new NewsletterType(), $entity);

        if($oRequest->isMethod("POST")) {
            $form->handleRequest($oRequest);

            if ($form->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_edit', array(
                    'id' => $entity->getId()
                )));
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
     * @Route("/{id}/edit", name="admin_newsletter_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterSubscription entity.');
        }

        $editForm = $this->createForm(new NewsletterType(), $entity);


        if($oRequest->isMethod("POST")) {
            $result = $editForm->handleRequest($oRequest);

            if ($editForm->isValid()) {
                $oEm->persist($entity);
                $oEm->flush();

                return $this->redirect($this->generateUrl('admin_newsletter_edit', array(
                    'id' => $entity->getId()
                )));
            }
        }

        return array(
            'menu'   => 'newsletters',
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Display a form to send the newsletter
     *
     * @Route("/{id}/send", name="admin_newsletter_send")
     * @Template()
     */
    public function sendAction($id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterSubscription entity.');
        }

        $editForm = $this->createForm(new NewsletterType(), $entity);

        $groups = $oEm->getRepository('AutoplanDBBundle:NewsletterGroup')->findBy(array(), array('name' => 'ASC'));

        $statuses = array();

        if($oRequest->isMethod("POST")) {

            $newsletter_email = $oRequest->get('autoplan_newsletter_email');
            $group_id = $oRequest->get('autoplan_newsletter_group');

            $emails = array();

            if (empty($newsletter_email)) {

                if (empty($group_id)) {
                    $subscribers = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findBy(array(
                        'active' => 1
                    ), array('name' => 'ASC'));
                } else {
                    $group = $oEm->getRepository('AutoplanDBBundle:NewsletterGroup')->find($group_id);
                    $subscribers = $group->getSubscribers();
                }

                if (!empty($subscribers)) {
                    foreach ($subscribers as $subscriber) {

                        $emails[] = array(
                            'id' => $subscriber->getId(),
                            'email' => $subscriber->getEmail(),
                            'slug' => $subscriber->getSlug()
                        );
                    }
                }
            } else {
                $emails[] = array(
                    'id' => 0,
                    'email' => $newsletter_email,
                    'slug' => 'no-slug'
                );
            }

            $cars = $entity->getCars();

            foreach ($emails as $email) {

                $html = $this->render('AutoplanSiteBundle:Newsletters:preview.html.twig', array(
                    'entity' => $entity,
                    'cars' => $cars,
                    'carCount' => empty($cars) ? 0 : count($cars),
                    'subscriber' => $email
                ));

                $dispatcher = $this->get('hip_mandrill.dispatcher');

                $message = new Message();
                $message
                    ->setFromEmail('info@autoservicehaarlem.nl')
                    ->setFromName('Auto Service Haarlem')
                    ->addTo($email['email'])
                    ->setSubject($entity->getSubject())
                    ->setHtml($html->getContent());

                $statuses[] = $dispatcher->send($message);
            }
        }

        return array(
            'statuses' => $statuses,
            'menu'   => 'newsletters',
            'groups' => $groups,
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }


    /**
     * Deletes a Page entity.
     *
     * @Route("/delete/{id}", name="admin_newsletter_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_newsletters'));
    }
}
