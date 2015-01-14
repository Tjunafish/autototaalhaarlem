<?php

namespace Autoplan\SiteBundle\Controller;

use Autoplan\DBBundle\Entity\Car;
use Autoplan\DBBundle\Entity\CarBrand;
use Autoplan\DBBundle\Entity\CarPhoto;
use Autoplan\DBBundle\Entity\CarView;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Autoplan\DBBundle\Entity\Page;
use Autoplan\ServiceBundle\Search\AutoplanSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CarsController extends Controller
{
    const SEARCH_BY_TYPE_DATA = 0;
    const SEARCH_BY_TYPE_CHASSIS = 1;
    const SEARCH_BY_TYPE_TEXT = 2;

    const MAX_PERPAGE = 25;

    /**
     * @Route("/zoeken", name="site_search")
     * @Template()
     */
    public function searchAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);
        if($oRequest->isMethod("POST")) {
            $aSearchParams = $this->parseSearchRequest($oRequest, $aSearchParams);
        }
        $bSearch = true;
        if($aSearchParams['searchBy'] == -1) {
            $bSearch = false;
        }

        $oSession->set('autoplan.search', $aSearchParams);
        $oSession->set('autoplan.lastPage', 'zoeken');

        $oEm = $this->getDoctrine()->getManager();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        if($oRequest->isXmlHttpRequest()) {

            if($aSearchParams['searchBy'] == -1) {
                $aSearchParams['searchBy'] = self::SEARCH_BY_TYPE_DATA;
            }

            // laad meer auto's request
            $iPage = $oRequest->get('page');
            $iTotal = $oSearcher->carTotal($aSearchParams);
            $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

            return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', array(
                'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE, $iPage),
                'max_pages' => $iMaxPages,
                'page' => $iPage,
            ));
        }



        $aParams = array(
            'menu' =>'',

            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),

            'new_cars' => $oSearcher->getNewCars(),

            'searched' => $bSearch,
        );

        return $aParams;
    }

    /**
     * @Template()
     */
    public function carResultsAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);

        $oSession->set('autoplan.search', $aSearchParams);

        $iTotal = $oSearcher->carTotal($aSearchParams);
        $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

        $aParams = array(

            'page' => 1,
            'max_pages' => $iMaxPages,
            'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE),
        );
        return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', $aParams);
    }

    /**
     * @Template()
     */
    public function carResultAction($car) {
        $aParams = array(

            'car' => $car,
        );
        return $this->render('AutoplanSiteBundle:Cars:carResult.html.twig', $aParams);
    }

    /**
     * @Template()
     */
    public function carSearchResultAction($car) {
        $aParams = array(

            'car' => $car,
        );
        return $this->render('AutoplanSiteBundle:Cars:carSearchResult.html.twig', $aParams);
    }


    /**
     * @Route("/aanbod-autoplan", name="site_offerings_autoplan")
     * @Template()
     */
    public function offeringsAutoplanAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $aSearchParams = array();

        $aSearchParams = $this->getSearchParams($aSearchParams);
        $aSearchParams['section'] = AutoplanSearcher::SECTION_AUTOPLAN;

        $oSession->set('autoplan.search', $aSearchParams);
        $oSession->set('autoplan.section', 'autoplan');
        $oSession->set('autoplan.lastPage', 'aanbod-autoplan');

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');


        if($oRequest->isXmlHttpRequest()) {
            // laad meer auto's request
            $iPage = $oRequest->get('page');

            $iTotal = $oSearcher->carTotal($aSearchParams);
            $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

            return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', array(
                'page' => $iPage,
                'max_pages' => $iMaxPages,
                'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE, $iPage),
            ));
        }
        $aPopular = array();

        foreach( $oEm->getRepository('AutoplanDBBundle:CarView')->createQueryBuilder('cv')
            ->select("c.id, COUNT(cv.id) as mycount")
            ->leftJoin("cv.car", "c")
            ->where("cv.created > :created")
            ->setParameter("created", date('Y-m-d', strtotime("-1 week")) . " - 00:00:00")
            ->andWhere("c.section = " . AutoplanSearcher::SECTION_AUTOPLAN)
            ->groupBy("cv.car")
            ->orderBy("mycount", "DESC")
            ->getQuery()
            ->getResult()
            as $aPopularCars) {
            /** @var CarView $oPopular */
            $aPopular[] = $aPopularCars['id'];
        }


        $aParams = array(

            'menu' => 'aanbod-autoplan',

            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),


            'new_cars' => $oSearcher->getNewCars(AutoplanSearcher::SECTION_AUTOPLAN),
            'ash_cars' => $oSearcher->getNewCars(AutoplanSearcher::SECTION_ASH),
            'inoffer' => $oSearcher->getOfferCars(AutoplanSearcher::SECTION_AUTOPLAN),
            'popular' => $oSearcher->getPopularCars($aPopular),

            'searched' => false,
        );

        return $this->render('AutoplanSiteBundle:Cars:offerings.html.twig', $aParams);
    }

    /**
     * @Route("/aanbod-autoplan/{brand}", name="site_offerings_autoplan_brand")
     * @Template()
     */
    public function offeringsAutoplanBrandAction($brand) {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $oEm = $this->getDoctrine()->getManager();

        /** @var CarBrand $oBrand */
        $oBrand = $oEm->getRepository('AutoplanDBBundle:CarBrand')->findOneBy(array('slug' => $brand));

        if(null===$oBrand) {
            throw $this->createNotFoundException();
        }

        $aSearchParams = array();

        $aSearchParams = $this->getSearchParams($aSearchParams);
        $aSearchParams['section'] = AutoplanSearcher::SECTION_AUTOPLAN;
        $aSearchParams['brand_id'] = $oBrand->getId();


        $oSession->set('autoplan.search', $aSearchParams);
        $oSession->set('autoplan.section', 'autoplan');
        $oSession->set('autoplan.lastPage', 'aanbod-autoplan');


        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        if($oRequest->isXmlHttpRequest()) {
            // laad meer auto's request
            $iPage = $oRequest->get('page');

            $iTotal = $oSearcher->carTotal($aSearchParams);
            $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

            return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', array(
                'page' => $iPage,
                'max_pages' => $iMaxPages,
                'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE, $iPage),
            ));
        }

        $aParams = array(

            'menu' => 'aanbod-autoplan',
            'brand' => $oBrand->getTitle(),
            'description' => $oBrand->getContent(),

            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),

            'searched' => false,
        );

        return $this->render('AutoplanSiteBundle:Cars:brandOffers.html.twig', $aParams);
    }

    /**
     * @Route("/aanbod", name="site_offerings_ash")
     * @Template()
     */
    public function offeringsASHAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $aSearchParams = array();

        $aSearchParams = $this->getSearchParams($aSearchParams);
        $aSearchParams['section'] = AutoplanSearcher::SECTION_ASH;

        $oSession->set('autoplan.search', $aSearchParams);
        $oSession->set('autoplan.section', 'ash');
        $oSession->set('autoplan.lastPage', 'aanbod-ash');

        $oEm = $this->getDoctrine()->getManager();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aPopular = array();

        foreach( $oEm->getRepository('AutoplanDBBundle:CarView')->createQueryBuilder('cv')
                     ->select("c.id, COUNT(cv.id) as mycount")
                     ->leftJoin("cv.car", "c")
                     ->where("cv.created > :created")
                     ->setParameter("created", date('Y-m-d', strtotime("-1 week")) . " - 00:00:00")
                     ->andWhere("c.section = " . AutoplanSearcher::SECTION_ASH)
                     ->groupBy("cv.car")
                     ->orderBy("mycount", "DESC")
                     ->getQuery()
                     ->getResult()
                 as $aPopularCars) {
            /** @var CarView $oPopular */
            $aPopular[] = $aPopularCars['id'];
        }


        if($oRequest->isXmlHttpRequest()) {
            // laad meer auto's request
            $iPage = $oRequest->get('page');

            $iTotal = $oSearcher->carTotal($aSearchParams);
            $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

            return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', array(
                'page' => $iPage,
                'max_pages' => $iMaxPages,
                'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE, $iPage),
            ));
        }

        $aParams = array(

            'menu' => 'aanbod-ash-specials',

            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),


            'new_cars' => $oSearcher->getNewCars(AutoplanSearcher::SECTION_ASH),
            'ash_cars' => $oSearcher->getNewCars(AutoplanSearcher::SECTION_ASH),
            'inoffer' => $oSearcher->getOfferCars(AutoplanSearcher::SECTION_ASH),
            'popular' => $oSearcher->getPopularCars($aPopular),

            'searched' => false,
        );

        return $this->render('AutoplanSiteBundle:Cars:offeringsASH.html.twig', $aParams);
    }

    /**
     * @Route("/aanbod-ash-specials/{brand}", name="site_offerings_ash_brand")
     * @Template()
     */
    public function offeringsASHBrandAction($brand) {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $oEm = $this->getDoctrine()->getManager();

        $oBrand = $oEm->getRepository('AutoplanDBBundle:CarBrand')->findOneBy(array('slug' => $brand));

        if(null===$oBrand) {
            throw $this->createNotFoundException();
        }

        $aSearchParams = array();

        $aSearchParams = $this->getSearchParams($aSearchParams);
        $aSearchParams['section'] = AutoplanSearcher::SECTION_ASH;
        $aSearchParams['brand_id'] = $oBrand->getId();


        $oSession->set('autoplan.search', $aSearchParams);
        $oSession->set('autoplan.section', 'ash');
        $oSession->set('autoplan.lastPage', 'aanbod-ash');



        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        if($oRequest->isXmlHttpRequest()) {
            // laad meer auto's request
            $iPage = $oRequest->get('page');

            $iTotal = $oSearcher->carTotal($aSearchParams);
            $iMaxPages = ceil($iTotal/self::MAX_PERPAGE);

            return $this->render('AutoplanSiteBundle:Cars:carResults.html.twig', array(
                'page' => $iPage,
                'max_pages' => $iMaxPages,
                'cars' => $oSearcher->carSearch($aSearchParams, 0, array(), self::MAX_PERPAGE, $iPage),
            ));
        }

        $aParams = array(

            'menu' => 'aanbod-ash-specials',
            'brand' => $oBrand->getTitle(),

            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),

            'searched' => false,
        );

        return $this->render('AutoplanSiteBundle:Cars:brandOffers.html.twig', $aParams);
    }

    /**
     * @Route("/{section}/{brand}/{sortname}_{id}", name="site_car")
     * @Template()
     */
    public function carDetailAction($section, $brand, $sortname, $id) {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $sLastPage = $oSession->set('autoplan.lastPage', 'home');
        $sPrevLink = $this->generateUrl('site_search');
        if($sLastPage == 'aanbod-autoplan') {
            $sPrevLink = $this->generateUrl('site_offerings_autoplan');
        } else if($sLastPage == 'aanbod-ash') {
            $sPrevLink = $this->generateUrl('site_offerings_ash');
        }

        /** @var Car $oCar */
        $oCar = $oEm->find('AutoplanDBBundle:Car', $id);

        if(null==$oCar) {
            throw new NotFoundHttpException();
        }

        if( $oCar->getCarBrand()->getSlug() !== $brand || $sortname !== $oCar->getSlug()) {
            throw new NotFoundHttpException();
        }

        $oCarView = new CarView();
        $oCarView->setCar($oCar);
        $oCarView->setClientIP($oRequest->getClientIp());
        $oEm->persist($oCarView);
        $oEm->flush();



        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);

        if($section == 'aanbod-ash-specials') {
            $aAlternatives = array_values($oSearcher->carSearchAlternatives($aSearchParams, AutoplanSearcher::SECTION_ASH, array(), AutoplanSearcher::MAX));
        } else {
            $aAlternatives = array_values($oSearcher->carSearchAlternatives($aSearchParams, AutoplanSearcher::SECTION_AUTOPLAN, array(), AutoplanSearcher::MAX));
        }





        $iIndex = 0;
        $i =0;
        foreach($aAlternatives as $aAlt) {
            if($aAlt['attrs']['object_id'] == $id) {
                $iIndex = $i;
                break;
            }
            $i++;
        }
        if($iIndex > 0) {
            $sPrev = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[($iIndex-1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[($iIndex-1)]['attrs']['sortname']),
                'id' => $aAlternatives[($iIndex-1)]['attrs']['object_id'],
            ));
        } else {
            $sPrev = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['sortname']),
                'id' => $aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['object_id'],
            ));
        }

        if($iIndex < (sizeof($aAlternatives)-1)) {
            $sNext = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[($iIndex+1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[($iIndex+1)]['attrs']['sortname']),
                'id' => $aAlternatives[($iIndex+1)]['attrs']['object_id'],
            ));
        } else {
            $sNext = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[0]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[0]['attrs']['sortname']),
                'id' => $aAlternatives[0]['attrs']['object_id'],
            ));
        }

        if($iIndex < (sizeof($aAlternatives)-6)) {
            $aRealAlts = array_slice($aAlternatives, $iIndex+1, 5);
        } else if(sizeof($aAlternatives) >= 6) {
            $aRealAlts = array_merge(array_slice($aAlternatives, $iIndex+1), array_slice($aAlternatives, 0, sizeof($aAlternatives)-$iIndex-1));
        } else {
            $aRealAlts = array();
            foreach($aAlternatives as $aAlt) {
                if($aAlt['attrs']['object_id'] !== $id) {
                    $aRealAlts[] = $aAlt;
                }
            }
        }

        $aParams = array(

            'menu' => $section,
            'car' => $oCar,
            'alts' => $aRealAlts,
            'next' => $sNext,
            'prev' => $sPrev,

            'overview_link' => $sPrevLink,
        );
        return $this->render('AutoplanSiteBundle:Cars:carDetail.html.twig', $aParams);
    }

    /**
     * @Route("/{section}/{brand}/{sortname}_{id}/print", name="site_car_print")
     * @Template()
     */
    public function carDetailPrintAction($section, $brand, $sortname, $id) {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $sLastPage = $oSession->set('autoplan.lastPage', 'home');
        $sPrevLink = $this->generateUrl('site_search');
        if($sLastPage == 'aanbod-autoplan') {
            $sPrevLink = $this->generateUrl('site_offerings_autoplan');
        } else if($sLastPage == 'aanbod-ash') {
            $sPrevLink = $this->generateUrl('site_offerings_ash');
        }

        /** @var Car $oCar */
        $oCar = $oEm->find('AutoplanDBBundle:Car', $id);

        if(null==$oCar) {
            throw new NotFoundHttpException();
        }

        if( $oCar->getCarBrand()->getSlug() !== $brand || $sortname !== $oCar->getSlug()) {
            throw new NotFoundHttpException();
        }

        $oCarView = new CarView();
        $oCarView->setCar($oCar);
        $oCarView->setClientIP($oRequest->getClientIp());
        $oEm->persist($oCarView);
        $oEm->flush();



        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);

        if($section == 'aanbod-ash-specials') {
            $oSearcher->setSection(AutoplanSearcher::SECTION_ASH);
        } else {
            $oSearcher->setSection(AutoplanSearcher::SECTION_AUTOPLAN);
        }

        $aAlternatives = array_values($oSearcher->carSearch($aSearchParams, 1, array(), AutoplanSearcher::MAX));



        $iIndex = 0;
        $i =0;
        foreach($aAlternatives as $aAlt) {
            if($aAlt['attrs']['object_id'] == $id) {
                $iIndex = $i;
                break;
            }
            $i++;
        }
        if($iIndex > 0) {
            $sPrev = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[($iIndex-1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[($iIndex-1)]['attrs']['sortname']),
                'id' => $aAlternatives[($iIndex-1)]['attrs']['object_id'],
            ));
        } else {
            $sPrev = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['sortname']),
                'id' => $aAlternatives[(sizeof($aAlternatives)-1)]['attrs']['object_id'],
            ));
        }

        if($iIndex < (sizeof($aAlternatives)-1)) {
            $sNext = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[($iIndex+1)]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[($iIndex+1)]['attrs']['sortname']),
                'id' => $aAlternatives[($iIndex+1)]['attrs']['object_id'],
            ));
        } else {
            $sNext = $this->generateUrl('site_car', array(
                'section' => $section,
                'brand' => $aAlternatives[0]['attrs']['brand_id_slug'],
                'sortname' => Urlizer::urlize($aAlternatives[0]['attrs']['sortname']),
                'id' => $aAlternatives[0]['attrs']['object_id'],
            ));
        }

        if($iIndex < (sizeof($aAlternatives)-6)) {
            $aRealAlts = array_slice($aAlternatives, $iIndex+1, 5);
        } else if(sizeof($aAlternatives) >= 6) {
            $aRealAlts = array_merge(array_slice($aAlternatives, $iIndex+1), array_slice($aAlternatives, 0, sizeof($aAlternatives)-$iIndex-1));
        } else {
            $aRealAlts = array();
            foreach($aAlternatives as $aAlt) {
                if($aAlt['attrs']['object_id'] !== $id) {
                    $aRealAlts[] = $aAlt;
                }
            }
        }

        $aParams = array(

            'menu' => $section,
            'car' => $oCar,
            'alts' => $aRealAlts,
            'next' => $sNext,
            'prev' => $sPrev,

            'overview_link' => $sPrevLink,
        );
        return $this->render('AutoplanSiteBundle:Cars:carDetailPrint.html.twig', $aParams);
    }

    /**
     * @Route("/{section}/{brand}/{sortname}_{id}/foto", name="site_car_photo")
     * @Template()
     */
    public function carPhotoAction($section, $brand, $sortname, $id) {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $sLastPage = $oSession->set('autoplan.lastPage', 'home');
        $sPrevLink = $this->generateUrl('site_search');
        if($sLastPage == 'aanbod-autoplan') {
            $sPrevLink = $this->generateUrl('site_offerings_autoplan');
        } else if($sLastPage == 'aanbod-ash') {
            $sPrevLink = $this->generateUrl('site_offerings_ash');
        }

        /** @var Car $oCar */
        $oCar = $oEm->find('AutoplanDBBundle:Car', $id);

        if(null==$oCar) {
            throw new NotFoundHttpException();
        }

        $aPhotos = $oCar->getPhotos();

        /** @var CarPhoto $oPhoto */
        $oPhoto = $aPhotos[0];

        $headers = array(
            'X-Robots-Tag'     => 'noindex',
            'Content-Type'     => 'image/png',
            'Content-Disposition' => 'inline; filename="'.$oPhoto->getImagePath().'"');
        return new Response(file_get_contents('uploads/cars/'.$oPhoto->getImagePath()), 200, $headers);
    }

    /**
     * @Template()
     */
    public function findCarAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        $sSection = $oSession->get('autoplan.section', 'ash');

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        if($sSection == 'autoplan') {
            $aNewCars = $oSearcher->getNewCars(AutoplanSearcher::SECTION_AUTOPLAN, 3);
        } else {
            $aNewCars = $oSearcher->getNewCars(AutoplanSearcher::SECTION_ASH, 3);
        }

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);
        if($oRequest->isMethod("POST")) {
            $aSearchParams = $this->parseSearchRequest($oRequest, $aSearchParams);
        }

        $oSession->set('autoplan.search', $aSearchParams);

        $aParams = array(
            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),

            'new_cars' => $aNewCars,


        );
        return $this->render('AutoplanSiteBundle:Cars:findCar.html.twig', $aParams);
    }

    /**
     * @Template()
     */
    public function findCarHomeAction() {

        $oRequest = $this->getRequest();
        $oSession = $oRequest->getSession();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aSearchParams = $oSession->get('autoplan.search', array());
        $aSearchParams = $this->getSearchParams($aSearchParams);
        if($oRequest->isMethod("POST")) {
            $aSearchParams = $this->parseSearchRequest($oRequest, $aSearchParams);
        }

        $oSession->set('autoplan.search', $aSearchParams);

        $aParams = array(
            'max_price' => (ceil($oSearcher->getMaxForField("price")/10000)*10000),

            'min_year' => $oSearcher->getMinForField("constructionyear"),
            'max_year' => $oSearcher->getMaxForField("constructionyear"),

            'max_kmcounter' => $oSearcher->getMaxForField("kmcounter"),

            'search' => $aSearchParams,

            'carCount' => $oSearcher->getCarCount(),
            'fuels' => $oSearcher->getFuels(),
            'brands' => $oSearcher->getBrands(),
            'models' => $oSearcher->getModels(),
            'models_json' => json_encode($oSearcher->getModels()),
        );
        return $this->render('AutoplanSiteBundle:Cars:findCarHome.html.twig', $aParams);
    }


    private function getSearchParams($aSearchParams) {
        $aSearchParams['searched'] = false;

        if(!isset($aSearchParams['min_price'])) {
            $aSearchParams['min_price'] = 0;
            if($aSearchParams['min_price'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }
        if(!isset($aSearchParams['max_price'])) {
            $aSearchParams['max_price'] = 0;
            if($aSearchParams['max_price'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }

        if(!isset($aSearchParams['brand_id'])) {
            $aSearchParams['brand_id'] = 0;
            if($aSearchParams['brand_id'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }
        if(!isset($aSearchParams['model_id'])) {
            $aSearchParams['model_id'] = 0;
            if($aSearchParams['model_id'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }

        if(!isset($aSearchParams['fuel_id'])) {
            $aSearchParams['fuel_id'] = 0;
            if($aSearchParams['fuel_id'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }

        if(!isset($aSearchParams['chassis_id'])) {
            $aSearchParams['chassis_id'] = 0;
            if($aSearchParams['chassis_id'] > 0) {
                $aSearchParams['searched'] = true;
                /*$aSearchParams['searchBy'] = self::SEARCH_BY_TYPE_CHASSIS;*/
            }
        }

        if(!isset($aSearchParams['year'])) {
            $aSearchParams['year'] = 0;
            if($aSearchParams['year'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }

        if(!isset($aSearchParams['kmcounter'])) {
            $aSearchParams['kmcounter'] = 0;
            if($aSearchParams['kmcounter'] > 0) {
                $aSearchParams['searched'] = true;
            }
        }

        if(!isset($aSearchParams['searchBy'])) {
            $aSearchParams['searchBy'] = -1;
        }

        if(!isset($aSearchParams['search_query'])) {
            $aSearchParams['search_query'] = '';
        }

        if(!isset($aSearchParams['section'])) {
            $aSearchParams['section'] = '';
        }

        return $aSearchParams;
    }

    /**
     * @param Request $oRequest
     * @param $aSearchParams
     * @return mixed
     */
    private function parseSearchRequest($oRequest, $aSearchParams) {
        if($oRequest->request->has('min_price')) {
            $aSearchParams['min_price'] = $oRequest->request->get('min_price');
        }
        if($oRequest->request->has('max_price')) {
            $aSearchParams['max_price'] = $oRequest->request->get('max_price');
        }

        if($oRequest->request->has('brand_id')) {
            $aSearchParams['brand_id'] = $oRequest->request->get('brand_id');
        }
        if($oRequest->request->has('model_id')) {
            $aSearchParams['model_id'] = $oRequest->request->get('model_id');
        }

        if($oRequest->request->has('fuel_id')) {
            $aSearchParams['fuel_id'] = $oRequest->request->get('fuel_id');
        }

        if($oRequest->request->has('year')) {
            $aSearchParams['year'] = $oRequest->request->get('year');
        }

        if($oRequest->request->has('kmcounter')) {
            $aSearchParams['kmcounter'] = $oRequest->request->get('kmcounter');
        }

        if($oRequest->request->has('chassis_id')) {
            $aSearchParams['chassis_id'] = $oRequest->request->get('chassis_id');
        }

        if($oRequest->request->has('search_query')) {
            $aSearchParams['search_query'] = $oRequest->request->get('search_query');
        }

        if($oRequest->request->has('section')) {
            $aSearchParams['section'] = $oRequest->request->get('section');
        }

        if($oRequest->request->has('searchBy')) {
            $aSearchParams['searchBy'] = $oRequest->request->get('searchBy');

            if(intval($oRequest->request->get('searchBy')) === self::SEARCH_BY_TYPE_DATA) {
                /*$aSearchParams['chassis_id'] = 0;*/
                $aSearchParams['search_query'] = '';
            } else if(intval($oRequest->request->get('searchBy')) === self::SEARCH_BY_TYPE_CHASSIS) {
                $aSearchParams['brand_id'] = 0;
                $aSearchParams['model_id'] = 0;
                $aSearchParams['fuel_id'] = 0;
                $aSearchParams['kmcounter'] = 0;
                $aSearchParams['year'] = 0;
                $aSearchParams['section'] ='';
                $aSearchParams['search_query'] = '';
            } else if(intval($oRequest->request->get('searchBy')) === self::SEARCH_BY_TYPE_TEXT) {
                $aSearchParams['min_price'] = 0;
                $aSearchParams['max_price'] = 0;
                $aSearchParams['brand_id'] = 0;
                $aSearchParams['model_id'] = 0;
                $aSearchParams['fuel_id'] = 0;
                $aSearchParams['year'] = 0;
                $aSearchParams['section'] ='';
                $aSearchParams['kmcounter'] = 0;
            }
        }
        return $aSearchParams;
    }

}
