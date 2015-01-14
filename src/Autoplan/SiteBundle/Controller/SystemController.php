<?php

namespace Autoplan\SiteBundle\Controller;

use Autoplan\DBBundle\Entity\Banner;
use Autoplan\DBBundle\Entity\CarView;
use Autoplan\SiteBundle\Form\CarSaleForm;
use Autoplan\SiteBundle\Form\ContactForm;
use Autoplan\SiteBundle\Form\NewsletterForm;
use Autoplan\SiteBundle\Form\FinanceForm;
use Doctrine\ORM\EntityManager;
use Sluggable\Fixture\Issue104\Car;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Autoplan\DBBundle\Entity\Page;
use Autoplan\ServiceBundle\Search\AutoplanSearcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SystemController extends Controller
{
    /**
     * @Route("/", name="site_home")
     * @Template()
     */
    public function homeAction() {
// die('hoi');

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $oCarSaleForm = $this->createForm(new CarSaleForm());
        $oContactForm = $this->createForm(new ContactForm());
        $aPopular = array();

        foreach( $oEm->getRepository('AutoplanDBBundle:CarView')->createQueryBuilder('cv')
                     ->select("c.id, COUNT(cv.id) as mycount")
                     ->leftJoin("cv.car", "c")
                     ->where("cv.created > :created")
                     ->setParameter("created", date('Y-m-d', strtotime("-1 week")) . " - 00:00:00")
                     ->groupBy("cv.car")
                     ->orderBy("mycount", "DESC")
                     ->getQuery()
                     ->getResult()
                 as $aPopularCars) {
            if(isset($aPopularCars['id']) && $aPopularCars['id'] > 0) {
                /** @var CarView $oPopular */
                $aPopular[] = $aPopularCars['id'];
            }
        }

        $aOffer = $oSearcher->getOfferCars();
        $aDeal = array_shift($aOffer);

        /** @var Car $oCar */
        $oCar = $oEm->find('AutoplanDBBundle:Car', $aDeal['attrs']['object_id']);
        
        return array(
            'menu' => 'home',

            'new_cars' => $oSearcher->getNewCars(0),
            'ash_cars' => $oSearcher->getNewCars(1),
            'inoffer' => $oSearcher->getOfferCars(),
            'popular' => $oSearcher->getPopularCars($aPopular),

            'car_sale_form' => $oCarSaleForm->createView(),
            'contact_form' => $oContactForm->createView(),

            'deal' => $oCar,

            'banners' => $oEm->getRepository('AutoplanDBBundle:Banner')->findBy(array('pos' => Banner::POS_HOME), array('sequence' => 'ASC')),
        );

    }

    /**
     * @Route("/finance", name="site_finance")
     * @Template()
     */
// This doesn't work for uri /finance
    public function financeAction() {
die('hoi');
        $oRequest = $this->getRequest();

        $oForm = $this->createForm(new FinanceForm());

        $aParams = array(
            'form' => $oForm->createView()
        );
        return $this->render('AutoplanSiteBundle:System:financeForm.html.twig', $aParams);
    }
    
    /**
     * @Route("/sitemap", name="site_sitemap")
     * @Template()
     */
    public function sitemapAction() {

        $oEm = $this->getDoctrine()->getManager();

        /** @var AutoplanSearcher $oSearcher */
        $oSearcher = $this->get('autoplan.search');

        $aBrands = $oSearcher->getBrands();
        $oSearcher->setSection(AutoplanSearcher::SECTION_ASH);
        $aBrandsASH = $oSearcher->getBrands();

        return array(

            'menu'              => 'sitemap',
            'brands_autoplan'   => $aBrands,
            'brands_ash'        => $aBrandsASH,
        );
    }

    /**
     * @Route("/contact", name="site_contact")
     * @Template()
     */
    public function contactAction() {

        $oEm = $this->getDoctrine()->getManager();

        return array(
            'menu' => 'contact'
        );
    }

    /**
     * @Route("/onze-zekerheden", name="site_certainties")
     * @Template()
     */
    public function certaintiesAction() {

        $oEm = $this->getDoctrine()->getManager();

        return array(
            'menu' => 'certainties',

            'banners' => $oEm->getRepository('AutoplanDBBundle:Banner')->findBy(array('pos' => Banner::POS_CERTAINTIES), array('sequence' => 'ASC')),
        );
    }

    /**
     * @Route("/veelgestelde-vragen", name="site_faq")
     * @Template()
     */
    public function faqAction() {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        return array(
            'menu' => 'faq',
            'faqs' => $oEm->getRepository('AutoplanDBBundle:FAQ')->findBy(array(), array('sequence' => 'ASC')),
        );
    }


    /**
     * @Route("/{firstLevel}", name="site_1st_level")
     * @Template()
     */
    public function firstLevelAction($firstLevel) {

        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oPage = $oEm->getRepository('AutoplanDBBundle:Page')->findOneBy(array('slug' => $firstLevel));
        if(null!==$oPage) {
            return $this->pageAction($oPage);
        }

        return array(
        );
    }

    /**
     * @param Page $oPage
     * @Template()
     */
    public function pageAction($oPage) {

        $oEm = $this->getDoctrine()->getManager();

        return $this->render('AutoplanSiteBundle:System:page.html.twig',
            array(
                'menu' => $oPage->getSlug(),
                'page' => $oPage
            )
        );
    }

    /**
     * @Template()
     */
    public function whoAreAction() {

        $oEm = $this->getDoctrine()->getManager();

        return $this->render('AutoplanSiteBundle:System:whoAre.html.twig',
            array(
                'employees' => $oEm->getRepository('AutoplanDBBundle:Employee')->findAll(array(), array('sequence', 'ASC'))
            )
        );
    }


    /**
     * @Template()
     */
    public function newsletterFormAction() {

        $oRequest = $this->getRequest();

        $oForm = $this->createForm(new NewsletterForm());

        $aParams = array(
            'form' => $oForm->createView()
        );
        return $this->render('AutoplanSiteBundle:System:newsletterForm.html.twig', $aParams);
    }

     /**
     * @Template()
     */
    public function financeFormAction() {

        $oRequest = $this->getRequest();

        $oForm = $this->createForm(new FinanceForm());

        $aParams = array(
            'form' => $oForm->createView()
        );
        return $this->render('AutoplanSiteBundle:System:financeForm.html.twig', $aParams);
    }



}
