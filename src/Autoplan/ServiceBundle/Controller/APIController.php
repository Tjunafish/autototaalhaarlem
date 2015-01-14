<?php
/**
 * Created by JetBrains PhpStorm.
 * User: simonsabelis
 * Date: 4/17/13
 * Time: 15:15
 * To change this template use File | Settings | File Templates.
 */

namespace Autoplan\ServiceBundle\Controller;


use Autoplan\DBBundle\Entity\Car;
use Autoplan\DBBundle\Entity\CarBrand;
use Autoplan\DBBundle\Entity\CarBrandModel;
use Autoplan\DBBundle\Entity\CarChassis;
use Autoplan\DBBundle\Entity\CarFuel;
use Autoplan\DBBundle\Entity\CarPhoto;
use Doctrine\ORM\EntityManager;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class SystemController
 * @package Autoplan\CMSBundle\Controller
 */
class APIController extends Controller{

    /**
     * @Route("/car_update", name="api_car_update")
     * @Template()
     */
    public function carUpdateAction() {
        
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();
        file_put_contents('/var/www/autoplan.nl/public_html/web/feed.json', json_encode($oRequest));

        /** @var Logger $oLogger */
        $oLogger = $this->get('logger');

        // if($oRequest->getClientIp() != '82.94.237.8' && $oRequest->getClientIp() != '82.94.240.8') {
        //     $oLogger->error("Car update request from untrusted IP: " . $oRequest->getClientIp());
        //     // Aanvraag komt niet van Hexon Server
        //     exit;
        // }

        $actie = $oRequest->get('actie');

        $iSection = $oRequest->get('section');

        $sHexonNr = $oRequest->get('voertuignr_hexon');

        /** @var Car $oCar */
        $oCar = $oEm->getRepository('AutoplanDBBundle:Car')->findOneBy(array(
            'hexonNr' => $sHexonNr
        ));

        $oLogger->info("Action " . $actie);

        if($actie == 'add' && null!==$oCar) {
            $actie = 'change';
        } elseif($actie == 'change' && null===$oCar) {
            $actie = 'add';
        }

        switch($actie) {
            case 'add':

                $oLogger->info("Adding car with hexonNr" . $sHexonNr);

                $oCar = new Car();
                $oCar->setSection($iSection);
                $oCar->setHexonNr($sHexonNr);
                $oCar->setCarNumber($oRequest->get('voertuignr'));

                $this->updateCarData($oCar, $oRequest);
                $oEm->persist($oCar);

                $oEm->flush();
                $this->updatePhotos($oCar, $oRequest);


                // if($iSection == 0) {
                    //posting to APN feeds
                    /*$oSession = FacebookSession::newAppSession('1408813742720787', 'caf61b12ee4c09a5bed6294ea9e64cee');
                    try {

                        $sUrl =  $this->generateUrl('site_car', array('section' => 'aanbod-autoplan', 'brand' => $oCar->getCarBrand()->getSlug(), 'sortname' => $oCar->getSlug(), 'id' => $oCar->getId()));

                        $response = (new FacebookRequest(
                                $oSession, 'POST', '/1451860231716907/feed', array(
                                    'link' => 'http://www.autoplannederland.nl/'.$sUrl,
                                    'message' => $oCar->getSortname(),
                                    'access_token' => 'CAAUBTwKwhxMBAB7Id1FVzHIkLqIqMRtWa5BlsJpR963fIkmR3wN9st96jIbPpvbf2xkogvOGSQqF0m5uUw97TWW0DDTlGgpO0U8BZCArDHMoZAdSPAGKE4FmhtL7eQbeWe5zNyblShARDYBlJp5oSFhh9wXiGebhWSDwsdyrNjhZCuose5v37qAc0hpqAMZD',
                                )
                            )
                        )->execute()->getGraphObject();
                        $oLogger->info("Car Posted to APN FB with id: " . $response->getProperty('id'));

                    } catch(FacebookRequestException $e) {

                        $oLogger->info("Exception occured, code: " . $e->getCode());
                        $oLogger->info(" with message: " . $e->getMessage());

                    }*/

                // } else {

                    /** @var UploaderHelper $helper */
                    // $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');

                    // //posting to ASH feeds
                    // // access token page ASH CAAUBTwKwhxMBAE74h9tCbp0jZATiS5ZCLauvRSkoz72EZCI25ZCrvjLsbI4HrHxN7Bypnndk42pJb7VdZCbuqkGaHI6vo5MrxDXu4oANo7nMDfcoHoWSRotEGVZBEEfTn3ZBTPKUBwuzvot7WAPjFMOl2rTgy9ZC2YnMf0T1G7ZBSw2825VkDaN2T1yprNl8lTZBoZD
                    // $oSession = FacebookSession::newAppSession('1408813742720787', 'caf61b12ee4c09a5bed6294ea9e64cee');
                    // try {

                    //     $sUrl =  $this->generateUrl('site_car', array('section' => 'aanbod-ash', 'brand' => $oCar->getCarBrand()->getSlug(), 'sortname' => $oCar->getSlug(), 'id' => $oCar->getId()));
                    //     $sUrlPhoto =  $this->generateUrl('site_car_photo', array('section' => 'aanbod-ash', 'brand' => $oCar->getCarBrand()->getSlug(), 'sortname' => $oCar->getSlug(), 'id' => $oCar->getId()));
                    //     //$helper->asset($oCar->getFirstPhoto(), 'image')

                    //     $response = (new FacebookRequest(
                    //         $oSession, 'POST', '/me/feed', array(
                    //             'link' => 'http://www.autoservicehaarlem.nl/'.$sUrl,
                    //             'picture' => 'http://www.autoservicehaarlem.nl'. $sUrlPhoto,
                    //             'message' => $oCar->getSortname(),
                    //             'access_token' => 'CAAUBTwKwhxMBAE74h9tCbp0jZATiS5ZCLauvRSkoz72EZCI25ZCrvjLsbI4HrHxN7Bypnndk42pJb7VdZCbuqkGaHI6vo5MrxDXu4oANo7nMDfcoHoWSRotEGVZBEEfTn3ZBTPKUBwuzvot7WAPjFMOl2rTgy9ZC2YnMf0T1G7ZBSw2825VkDaN2T1yprNl8lTZBoZD',
                    //         )
                    //     )
                    //     )->execute()->getGraphObject();

                    //     $oLogger->info("Car Posted to ASH FB with id: " . $response->getProperty('id'));

                    // } catch(FacebookRequestException $e) {

                    //     $oLogger->info("Exception occured, code: " . $e->getCode());
                    //     $oLogger->info(" with message: " . $e->getMessage());

                    // }
                // }

                $oEm->flush();
                break;

            case 'change':

                $this->updateCarData($oCar, $oRequest);
                $this->updatePhotos($oCar, $oRequest);

                $oEm->flush();

                break;

            case 'delete':
                // Voertuig verwijderen uit database

                if(null!==$oCar) {
                    $oEm->remove($oCar);
                    $oEm->flush();
                }
                break;
        }


        return new Response("1");
    }

    /**
     * @param Car $oCar
     * @param Request $oRequest
     */
    protected function updateCarData($oCar, $oRequest) {
        $oCar->setCarBrand($this->getBrand($oRequest->get('merk')));
        $oCar->setCarBrandModel($this->getBrandModel($oCar->getCarBrand(),$oRequest->get('model')));
        $oCar->setType($oRequest->get('type'));
        $oCar->setSortname((string)$oCar);
        $oCar->setPrice($oRequest->get('verkoopprijs_particulier'));
        $oCar->setMonthlyprice($oRequest->get('lease_maandbedrag'));

        $oCar->setCarFuel($this->getFuel($oRequest->get('brandstof')));
        $oCar->setCarChassis($this->getChassis($oRequest->get('carrosserie')));

        $oCar->setDoors($oRequest->get('aantal_deuren'));
        $oCar->setKmcounter($oRequest->get('tellerstand'));
        $oCar->setKmcounterunit($oRequest->get('tellerstand_eenheid'));

        $oCar->setTransmission($oRequest->get('transmissie'));
        $oCar->setTransmissionCount($oRequest->get('aantal_versnellingen'));

        $oCar->setTopspeed($oRequest->get('topsnelheid'));

        $oCar->setEnergylabel($oRequest->get('energielabel'));

        $oCar->setTaxtype($oRequest->get('btw_marge'));
        $oCar->setTaxmin($oRequest->get('wegenbelasting_kwartaal_min'));
        $oCar->setTaxmax($oRequest->get('wegenbelasting_kwartaal_max'));

        $oCar->setBasecolor($oRequest->get('basiskleur'));
        $oCar->setPaintType($oRequest->get('laksoort'));
        $oCar->setFabric($oRequest->get('bekleding'));
        $oCar->setFabricColor($oRequest->get('interieurkleur'));

        $oCar->setConstructionyear($oRequest->get('bouwjaar'));

        //actieprijs
        //verkoopprijs handel
        $oCar->setRemarks($oRequest->get('opmerkingen'));
        $oCar->setMaxtowing($oRequest->get('max_trekgewicht'));
        $oCar->setWeight($oRequest->get('massa'));
        $oCar->setCilinder($oRequest->get('cilinderinhoud'));
        $oCar->setCilindercount($oRequest->get('aantal_cilinders'));
        $oCar->setMotorpower($oRequest->get('vermogen_motor_pk'));
        $oCar->setSeats($oRequest->get('aantal_zitplaatsen'));

        $oCar->setAccessoires($oRequest->get('accessoires'));

    }

    /**
     * @param Car $oCar
     * @param Request $oRequest
     */
    protected function updatePhotos($oCar, $oRequest) {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
        if(sizeof($oCar->getPhotos()) > 0) {
            foreach($oCar->getPhotos() as $oPhoto) {
                $oEm->remove($oPhoto);
            }
        }
        $oEm->flush();

        $fotos = explode(',', $oRequest->get('afbeeldingen'));

        $oFirstPhoto = null;

        foreach($fotos as $foto_nr => $foto_url) {

            $sFileName = tempnam(sys_get_temp_dir(), 'car');
            file_put_contents($sFileName, file_get_contents(stripcslashes($foto_url)));
            $oFile = new UploadedFile($sFileName, "foto " . $foto_nr. ".jpg", "image/jpg", filesize($sFileName), null, true);
            $oCarPhoto = new CarPhoto();
            $oCarPhoto->setCar($oCar);
            $oCarPhoto->setImage($oFile);
            $oCarPhoto->setSequence($foto_nr);
            $oEm->persist($oCarPhoto);

            if(null===$oFirstPhoto) {
                $oFirstPhoto = $oCarPhoto;
            }
        }

        if(null!==$oFirstPhoto) {
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagePath = $helper->asset($oFirstPhoto, 'image');

            $avalancheService = $this->get('imagine.cache.path.resolver');
            $cachedImage = $avalancheService->getBrowserPath($imagePath, 'car_thumb');
            $oCar->setFirstImageUrl(str_replace('/app_dev.php','',$cachedImage));
        }

        $oEm->flush();
        $oEm->refresh($oCar);
    }

    protected function getBrand($sBrand) {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oBrand = $oEm->getRepository('AutoplanDBBundle:CarBrand')->findOneBy(array(
            'title' => $sBrand
        ));
        if(null==$oBrand) {
            $oBrand = new CarBrand();
            $oBrand->setTitle($sBrand);
            $oEm->persist($oBrand);
            $oEm->flush($oBrand);
        }
        return $oBrand;
    }

    protected function getBrandModel($oBrand, $sModel) {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oModel = $oEm->getRepository('AutoplanDBBundle:CarBrandModel')->findOneBy(array(
            'carBrand' => $oBrand->getId(),
            'title' => $sModel
        ));
        if(null==$oModel) {
            $oModel = new CarBrandModel();
            $oModel->setCarBrand($oBrand);
            $oModel->setTitle($sModel);
            $oEm->persist($oModel);
            $oEm->flush($oModel);
        }
        return $oModel;
    }

    protected function getChassis($sChassis) {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oChassis = $oEm->getRepository('AutoplanDBBundle:CarChassis')->findOneBy(array(
            'title' => $sChassis
        ));
        if(null==$oChassis) {
            $oChassis = new CarChassis();
            $oChassis->setTitle($sChassis);
            $oEm->persist($oChassis);
            $oEm->flush($oChassis);
        }
        return $oChassis;
    }

    protected function getFuel($sFuel) {
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oFuel = $oEm->getRepository('AutoplanDBBundle:CarFuel')->findOneBy(array(
            'title' => $sFuel
        ));
        if(null==$oFuel) {
            $oFuel = new CarFuel();
            $oFuel->setTitle($sFuel);
            $oEm->persist($oFuel);
            $oEm->flush($oFuel);
        }
        return $oFuel;
    }

}