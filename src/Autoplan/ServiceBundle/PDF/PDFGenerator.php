<?php
namespace Autoplan\ServiceBundle\PDF;

use Autoplan\DBBundle\Entity\Car;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

class PDFGenerator extends ContainerAware
{
    /**
     * @var TwigEngine $oTemplating
     */
    private $oTemplating;

    /**
     * @param Container $oContainer
     */
    public  function __construct($oContainer) {
        $this->setContainer($oContainer);

        $this->oTemplating = $this->container->get('twig');
    }

    public function generateAutokaart(Car $oCar)
    {
        $aParams = array();

        if($oCar->getSection() == Car::SECTION_AUTOPLAN) {
            $sHtml = $this->oTemplating->render('AutoplanCMSBundle::Car/carPdfAutoplan.html.twig', array('car' => $oCar));
        } else {
            $sHtml = $this->oTemplating->render('AutoplanCMSBundle::Car/carPdfASH.html.twig', array('car' => $oCar));
        }

        //return new Response($sHtml);

        /**
         * $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
        'header-html' => $this->sHttpHost.'/factuur/header',
        'footer-html' => $this->sHttpHost.'/factuur/footer',
        "lowquality"=>false,
        "margin-top"=>40,
        "header-spacing"=>45,
        "margin-bottom"=>25,
        "footer-spacing"=>0,
        )
         */

        return new Response(
            $this->container->get('knp_snappy.pdf')->getOutputFromHtml($sHtml, array(
                'footer-html' => 'http://www.autoservicehaarlem.nl/cms/'.($oCar->getSection() == Car::SECTION_AUTOPLAN ? 'pdf_autoplan_footer' : 'pdf_ash_footer'),
                /*"lowquality"=>false,*/
                "margin-top"=>0,
                "header-spacing"=>0,
                "margin-bottom"=>15,
                "margin-left"=>0,
                "margin-right"=>0,
                "footer-spacing"=>0,
            )),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment;filename='.'Autoplan Autokaart '.$oCar.".pdf"
            )
        );
    }
    
}