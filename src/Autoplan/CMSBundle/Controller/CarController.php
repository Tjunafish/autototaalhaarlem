<?php

namespace Autoplan\CMSBundle\Controller;

use Autoplan\CMSBundle\Form\FAQType;
use Autoplan\DBBundle\Entity\Car;
use Autoplan\DBBundle\Entity\FAQ;
use Autoplan\ServiceBundle\PDF\PDFGenerator;
use Doctrine\ORM\EntityManager;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Car controller.
 *
 * @Route("/autos")
 */
class CarController extends Controller
{

    /**
     * Lists all Car entities.
     *
     * @Route("/", name="admin_cars")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        if($oRequest->get('section','') !== '') {
            $oSession->set('cars.section', $oRequest->get('section'));
        }

        $sSection = $oSession->get('cars.section', 'autoplan');


        return array(
            'menu' => 'cars',
            'section' => $sSection,
            'cars' => $oEm->getRepository('AutoplanDBBundle:Car')->findBy(array('section' => ($sSection == "autoplan" ? 0 : 1)), array('created' => 'DESC'))
        );
    }


    /**
     * Deletes a Car entity.
     *
     * @Route("/delete/{id}", name="admin_car_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $oEm = $this->getDoctrine()->getManager();
        $entity = $oEm->getRepository('AutoplanDBBundle:Car')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }

        $oEm->remove($entity);
        $oEm->flush();

        return $this->redirect($this->generateUrl('admin_cars'));
    }

    /**
     * Updates car property
     *
     * @Route("/update", name="admin_car_update")
     */
    public function updateCarAction(Request $oRequest)
    {
        $oEm = $this->getDoctrine()->getManager();
        /** @var Car $entity */
        $entity = $oEm->getRepository('AutoplanDBBundle:Car')->find($oRequest->get('id'));
        $sValue = '';

        switch($oRequest->get('key')) {
            case 'isOffer':
                $entity->setIsOffer($oRequest->get('value') == "true");
                $oEm->flush();
                $sValue = var_export($entity->getIsOffer(), true);
                break;
        }

        $oEm->flush();

        return new Response(json_encode(array('result' => array('success' => true, 'key' => $oRequest->get('key'), 'value' => $sValue))));
    }

    /**
     * @Route("/pdf/{id}", name="admin_car_pdf")
     */
    public function pdfAction($id)
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();
        $oCar = $oEm->getRepository('AutoplanDBBundle:Car')->find($id);

        if (!$oCar) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }

        /** @var PDFGenerator $oPDFGenerator */
        $oPDFGenerator = $this->get('autoplan.pdf');
        return $oPDFGenerator->generateAutokaart($oCar);

        //$this->getResponse()->setSlot('canonical', $this->generateUrl('3rd_level', array('1st_level' => $oProvince->getSlug(), '2nd_level' => $oCity->getSlug(), '3rd_level' => $oProperty->getSlug()), true));

        //header('Link: <'.$this->generateUrl('3rd_level', array('1st_level' => $oProvince->getSlug(), '2nd_level' => $oCity->getSlug(), '3rd_level' => $oProperty->getSlug()), true).'>; rel="canonical"');

        //$oPdfGenerator = new PdfGenerator();
        //$oPdfGenerator->generateBrochureWebsite($oProperty);


    }

    /**
     * @Route("/facebook/{id}", name="admin_car_facebook")
     */
    public function facebookAction($id)
    {
        $oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();
        /** @var Car $oCar */
        $oCar = $oEm->getRepository('AutoplanDBBundle:Car')->find($id);

        if (!$oCar) {
            throw $this->createNotFoundException('Unable to find Car entity.');
        }
        //posting to APN feeds
        $oSession = FacebookSession::newAppSession('1408813742720787', 'caf61b12ee4c09a5bed6294ea9e64cee');
        try {

            $sUrl =  $this->generateUrl('site_car', array('section' => 'aanbod-ash', 'brand' => $oCar->getCarBrand()->getSlug(), 'sortname' => $oCar->getSlug(), 'id' => $oCar->getId()));

            //0a307c7c197c877c24a372e88276e0d4

            //$oSession->getLongLivedSession('1408813742720787', 'caf61b12ee4c09a5bed6294ea9e64cee');

            //$oSession = new FacebookSession('AQDbYHSQ3ovU_U9OUo-Nv4wTZwmzhXilUJcp_JbJcsRVbJT79WdfD_dpIGR7JlwehjDMZ-eJPaiQcLEy6v7F9w4lFPr131MiJ6gANQ94CiG-fW0d-xoe_C9puEHNeaW3AnXRZnUw6HtCO_d63LvH1T91FAc-3ktFdIDZXBIiKZnAlclGL9kLXMqCxPBg0P8h-pwbFXm67U1snzha-uSk-I3LfjxZXPccyFeQvz1YCRfqTutYSKQxI6R4hEGwGBuRTeRFQpGP2bVoXvKiKda6OcQwT5vPSg9mj66tO7ZvY9vpswB40DxnFrpZNky2sAZyG_M#_=_');
            $oRequest = new FacebookRequest(
                $oSession, 'GET', '/me/accounts', array(
                //'access_token' => '0_LFUYdnQLkfIs_YjXAqTNpGXh0'
                //'access_token'=> '1408813742720787|0_LFUYdnQLkfIs_YjXAqTNpGXh0'
                'access_token' => 'CAAUBTwKwhxMBAKizpXyHsBaBCUGmyz7DjMuQIaELZAvT8VB7kfsN7KS0LFZADSnlqQK05r6xcGZCBNy10nLIK4hZABDQzoSh8NIN7A7QmqZCcDM1dxhdMwayZA8epzjIQlfnTjyY7r7TvpIvuqiPqWhPNZCumn4sz1d0W3Y59Qypsl0ZAYBItXD5'
            ));
            $response = $oRequest->execute()->getGraphObject();
            var_dump($response);
            die();


            $oRequest = new FacebookRequest(
                $oSession, 'POST', '/me/feed', array(
                'link' => 'http://www.autoservicehaarlem.nl/'.$sUrl,
                'message' => $oCar->getSortname(),
                'access_token' => 'CAAUBTwKwhxMBAB7Id1FVzHIkLqIqMRtWa5BlsJpR963fIkmR3wN9st96jIbPpvbf2xkogvOGSQqF0m5uUw97TWW0DDTlGgpO0U8BZCArDHMoZAdSPAGKE4FmhtL7eQbeWe5zNyblShARDYBlJp5oSFhh9wXiGebhWSDwsdyrNjhZCuose5v37qAc0hpqAMZD'
            ));
            $response = $oRequest->execute()->getGraphObject();
            var_dump($response);
            die();

        } catch(FacebookRequestException $e) {

            var_dump($e);
            die();

        }


        //$this->getResponse()->setSlot('canonical', $this->generateUrl('3rd_level', array('1st_level' => $oProvince->getSlug(), '2nd_level' => $oCity->getSlug(), '3rd_level' => $oProperty->getSlug()), true));

        //header('Link: <'.$this->generateUrl('3rd_level', array('1st_level' => $oProvince->getSlug(), '2nd_level' => $oCity->getSlug(), '3rd_level' => $oProperty->getSlug()), true).'>; rel="canonical"');

        //$oPdfGenerator = new PdfGenerator();
        //$oPdfGenerator->generateBrochureWebsite($oProperty);


    }

}
