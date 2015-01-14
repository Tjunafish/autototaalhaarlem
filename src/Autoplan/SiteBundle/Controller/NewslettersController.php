<?php

namespace Autoplan\SiteBundle\Controller;

use Autoplan\DBBundle\Entity\Car;
use Autoplan\DBBundle\Entity\Newsletter;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Autoplan\ServiceBundle\Search\AutoplanSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewslettersController extends Controller
{

    /**
     * @Route("/newsletter/{slug}/{subscriber_slug}/{subscriber_id}", defaults={"subscriber_slug" = "", "subscriber_id" = ""}, name="newsletter_preview")
     * @Template()
     */
    public function previewAction($slug, $subscriber_slug, $subscriber_id) {

        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:Newsletter')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $cars = $entity->getCars();
        $subscriber = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findOneBy(array(
        	'slug' => $subscriber_slug,
        	'id' => $subscriber_id
		));

        return array(
            'entity' => $entity,
            'cars' => $cars,
            'carCount' => empty($cars) ? 0 : count($cars),
            'subscriber' => $subscriber
        );
    }

    /**
     * @Route("/unsubscribe/{slug}/{id}", name="newsletter_unsubscribe")
     * @Template()
     */
    public function unsubscribeAction($slug, $id) {

        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();

        $entity = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findOneBy(array(
        	'slug' => $slug,
        	'id' => $id
		));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterSubscription entity.');
        }

        $entity->setActive(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return array(
        	'page' => array(
        		'slug' => 'disclaimer'
    		)
    	);
    }

}
