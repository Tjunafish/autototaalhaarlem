<?php
/**
 * Created by JetBrains PhpStorm.
 * User: simonsabelis
 * Date: 6/26/13
 * Time: 12:13 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Autoplan\ServiceBundle\Twig;


use Autoplan\DBBundle\Entity\Text;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;
use VJP\WoningDossier\DBBundle\Entity\AssociationSaleType;
use VJP\WoningDossier\DBBundle\Entity\AssociationScheme;
use VJP\WoningDossier\DBBundle\Entity\Dossier;
use VJP\WoningDossier\DBBundle\Entity\DossierProcess;
use VJP\WoningDossier\DBBundle\Entity\DossierUpdate;
use VJP\WoningDossier\DBBundle\Entity\LeadAction;
use VJP\WoningDossier\DBBundle\Entity\ProcessType;
use VJP\WoningDossier\DBBundle\Entity\User;
use VJP\WoningDossier\ServiceBundle\External\SyncManager;
use VJP\WoningDossier\ServiceBundle\Tools\MortgageCalculator;


class AutoplanTwigExtension extends \Twig_Extension implements ContainerAwareInterface {

    /**
     * @var ContainerInterface
     *
     * @api
     */
    protected $container;

    /**
     * @var EntityManager $oEm
     */
    public $oEm;

    private $aCycles = array();

    /**
     * @param Registry $oRegistry
     */
    public  function __construct($oContainer) {
        $this->setContainer($oContainer);

        $this->oEm = $this->container->get('doctrine')->getManager();
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('rad2deg', array($this, 'rad2deg')),


            new \Twig_SimpleFilter('tinyString', array($this, 'tinyString')),

            new \Twig_SimpleFilter('seoReplace', array($this, 'seoReplace')),

            new \Twig_SimpleFilter('carCostsDaily', array($this, 'carCostsDaily')),
            new \Twig_SimpleFilter('carCosts', array($this, 'carCosts')),

            new \Twig_SimpleFilter('fuelTitle', array($this, 'fuelTitle')),
            new \Twig_SimpleFilter('transmissionTitle', array($this, 'transmissionTitle')),

            new \Twig_SimpleFilter('slugify', array($this, 'slugify')),




        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('cycleVar', array($this, 'cycleVar')),

            new \Twig_SimpleFunction('textBlock', array($this, 'textBlock')),
            new \Twig_SimpleFunction('textBlockTitle', array($this, 'textBlockTitle')),

            new \Twig_SimpleFunction('carUrl', array($this, 'carUrl')),
            new \Twig_SimpleFunction('carUrlPrint', array($this, 'carUrlPrint')),
        );
    }

    public function getName() {
        return "AutoplanTwigExtension";
    }

    public function cycleVar($aVar) {
        $sKey = sha1(implode(',',$aVar));
        if(!isset($this->aCycles[$sKey])) {
            $this->aCycles[$sKey] = 0;
        } else {
            if($this->aCycles[$sKey] < sizeof($aVar)-1 ) {
                $this->aCycles[$sKey]++;
            } else {
                $this->aCycles[$sKey] = 0;
            }
        }

        return $aVar[$this->aCycles[$sKey]];
    }

    public function rad2deg($sVal) {
        return rad2deg($sVal);
    }

    public function tinyString($str, $iMax) {
        if(strlen($str) > $iMax) {
            return substr($str, 0, $iMax) . "...";
        } else {
            return $str;
        }
    }

    public function seoReplace($sString, $aSeo) {

        foreach($aSeo as $sKey => $val) {
            $sString = str_replace('%'.$sKey.'%', (string)$val, $sString);
        }
        return $sString;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function carUrl($sSection, $sBrand, $sSort, $iID, $absolute = false) {
        /** @var Router $oRouting */
        $oRouting = $this->container->get('router');

        if($sSection == 0) {
            return $oRouting->generate('site_car', array('section' => 'aanbod-autoplan', 'brand' => $sBrand, 'sortname' => $sSort, 'id' => $iID), $absolute);
        } else {
            return $oRouting->generate('site_car', array('section' => 'aanbod-ash-specials', 'brand' => $sBrand, 'sortname' => $sSort, 'id' => $iID), $absolute);
        }
    }

    public function carUrlPrint($sSection, $sBrand, $sSort, $iID) {
        /** @var Router $oRouting */
        $oRouting = $this->container->get('router');

        if($sSection == 0) {
            return $oRouting->generate('site_car_print', array('section' => 'aanbod-autoplan', 'brand' => $sBrand, 'sortname' => $sSort, 'id' => $iID));
        } else {
            return $oRouting->generate('site_car_print', array('section' => 'aanbod-ash-specials', 'brand' => $sBrand, 'sortname' => $sSort, 'id' => $iID));
        }
    }

    /**
     * @param float $price
     * @return mixed
     */
    public function carCosts($price) {

        return ($price * 0.20);
    }

    /**
     * @param float $price
     * @return mixed
     */
    public function carCostsDaily($price) {

        return ($price * 0.20) / (3 * 365);
    }

    public function fuelTitle($fuel) {
        switch ($fuel) {
            case 'B':
                return "Benzine";
            case 'D':
                return "Diesel";
            case 'H':
                return "Hybride";
            case 'L':
                return "LPG";
        }
    }

    public function transmissionTitle($transmission) {
        switch ($transmission) {
            case 'H':
                return "Handgeschakeld";
            case 'A':
                return "Automaat";
            case 'S':
                return "Semi-automaat";
        }
    }

    public function slugify($string) {
        return Urlizer::urlize($string);
    }

    public function textBlock($sBlock) {
        /** @var Text $oText */
        $oText = $this->oEm->getRepository('AutoplanDBBundle:Text')->findOneBy(array('textKey' => $sBlock));
        if(null!==$oText) {
            return $oText->getContent();
        } else {
            return "";
        }
    }

    public function textBlockTitle($sBlock) {
        /** @var Text $oText */
        $oText = $this->oEm->getRepository('AutoplanDBBundle:Text')->findOneBy(array('textKey' => $sBlock));
        if(null!==$oText) {
            return $oText->getTitle();
        } else {
            return "";
        }
    }
}
