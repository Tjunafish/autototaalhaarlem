<?php

namespace Autoplan\SiteBundle\Controller;

use Autoplan\DBBundle\Entity\NewsletterSubscription;
use Autoplan\SiteBundle\Form\CarSaleForm;
use Autoplan\SiteBundle\Form\ContactForm;
use Autoplan\SiteBundle\Form\NewsletterForm;
use Autoplan\SiteBundle\Form\FinanceForm;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class ApiController
 * @Route("/api")
 */
class ApiController extends Controller
{
    const EMAIL_RECIPIENT = "info@autoservicehaarlem.nl"; // mailto: info@autoservicehaarlem.nl

    /**
     * @Route("/inruil-submit", name="api_car_sale_submit")
     * @Template()
     */
    public function carSaleSubmitAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oForm = $this->createForm(new CarSaleForm(), array());

        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sName = $oForm->get('name')->getData();
            $sEmail = $oForm->get('email')->getData();
            $sPhone = $oForm->get('phone')->getData();
            $sLicense = $oForm->get('license')->getData();
            $sMessage = $oForm->get('message')->getData();



            $message = \Swift_Message::newInstance()
                ->setSubject('Nieuwe inruilaanvraag')
                ->setFrom('info@autoservicehaarlem.nl')
                ->setTo(self::EMAIL_RECIPIENT)
                ->setBody(
                    "
                        Nieuwe inruilaanvraag ontvangen.
                        Naam: $sName
                        Email: $sEmail
                        Telefoonnummer: $sPhone
                        Kenteken: $sLicense
                        Bericht:
                        $sMessage
                    "
                );
            ;
            $this->get('mailer')->send($message);

            return new Response(json_encode(array('result' => "ok")));
        } else {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "nok")));
        }
    }

    /**
     * @Route("/contact-submit", name="api_contact_submit")
     * @Template()
     */
    public function contactSubmitAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oForm = $this->createForm(new ContactForm(), array());

        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sName = $oForm->get('name')->getData();
            $sPhone = $oForm->get('phone')->getData();



            $message = \Swift_Message::newInstance()
                ->setSubject('Nieuwe contactaanvraag')
                ->setFrom('info@autoservicehaarlem.nl')
                ->setTo(self::EMAIL_RECIPIENT)
                ->setBody(
                    "
                        Nieuwe contactaanvraag ontvangen.
                        Naam: $sName
                        Telefoonnummer: $sPhone
                    "
                );
            ;
            $this->get('mailer')->send($message);

            return new Response(json_encode(array('result' => "ok")));
        } else {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "nok")));
        }
    }

    /**
     * @Route("/nieuwbsrief-submit", name="api_newsletter_submit")
     * @Template()
     */
    public function newsletterSubmitAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oForm = $this->createForm(new NewsletterForm(), array());

        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sName = $oForm->get('name')->getData();
            $sEmail = $oForm->get('email')->getData();


            $oExisting = $oEm->getRepository('AutoplanDBBundle:NewsletterSubscription')->findOneBy(array('email' => $sEmail));
            if(null===$oExisting) {
                $oNewsletterSubscription = new NewsletterSubscription();
                $oNewsletterSubscription->setEmail($sEmail);
                $oNewsletterSubscription->setName($sName);
                $oEm->persist($oNewsletterSubscription);
                $oEm->flush();
            }

            /*$message = \Swift_Message::newInstance()
                ->setSubject('Nieuwe contactaanvraag')
                ->setFrom('info@autoplannederland.nl')
                ->setTo(self::EMAIL_RECIPIENT)
                ->setBody(
                    "
                        Nieuwe nieuwsbrief inschrijving ontvangen.
                        Naam: $sName
                        E-mailadres: $sEmail
                    "
                );
            ;
            $this->get('mailer')->send($message);*/

            return new Response(json_encode(array('result' => "ok")));
        } else {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "nok")));
        }
    }

    /**
     * @Route("/contactGeneral", name="api_sor_contact_submit_general")
     * @Template()
     */
    public function contactSubmitGeneralAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();
		
        $oForm = $this->createForm(new ContactFormGeneral(), array());

        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sFirstName = $oForm->get('name')->getData();
            $sLastName = $oForm->get('lastname')->getData();
            $sEmail = $oForm->get('email')->getData();
            $sPhone = $oForm->get('phone')->getData();
            $sMessage = $oForm->get('message')->getData();

            /** @var MailingManager $oMailer */
            $oMailer = $this->get('vjp.manager.mailing');
            $oMailer->mailContactRequestGeneral($sFirstName, $sLastName, $sEmail, $sPhone, $sMessage);


            return new Response(json_encode(array('result' => "ok")));
        } else {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "nok")));
        }
    }
	

    
    /**
     * @Route("/subscribe_newsletter", name="api_sor_subscribe_newsletter")
     * @Method("POST")
     * @Template()
     */
    public function subscribeNewsletterAction()
    {
    	$oRequest = $this->getRequest();
        $oEm = $this->getDoctrine()->getManager();
    	
    	$sEmail = $oRequest->request->get('email');
    	
    	$notBlankConstraint = new Assert\NotBlank();
    	$notBlankConstraint->message = 'Verplicht veld';
    	
    	$emailConstraint = new Assert\Email();
    	$emailConstraint->message = 'Ongeldig e-mailadres';
    	
    	$constraints = array($notBlankConstraint, $emailConstraint);
    	
    	$errorList = $this->get('validator')->validateValue(
    			$sEmail,
    			$constraints
    	);
    	
    	if(count($errorList) == 0) {

            /** @var MailingManager $oMailer */
            $oMailer = $this->get('vjp.manager.mailing');

            /** @var Subscriber $oSubscriber */
            $oSubscriber = $oEm->getRepository('VJPWoningDossierDBBundle:Subscriber')->findOneBy(array(
                'email' => strtolower($sEmail)
            ));
            if(null===$oSubscriber) {
                $oSubscriber = new Subscriber();
                $oSubscriber->setEmail(strtolower($sEmail));
                $oSubscriber->setPriceFrom(0);
                $oSubscriber->setPriceTo(0);
                $oSubscriber->setSurfaceFrom(0);
                $oSubscriber->setSurfaceTo(0);
                $oSubscriber->setRoomsFrom(0);
                $oSubscriber->setRoomsTo(0);
                $oEm->persist($oSubscriber);
                $oEm->flush();
            }
            $oMailer->mailNewsletterSubscription($oSubscriber);
    		return new Response(json_encode(array('result' => "ok")));
    	} else {
    		foreach($errorList as $error) {
    			$errors[] = $error->getMessage();
    		}
    		return new Response(json_encode(array('errors'=> $errors, 'result' => "nok")));
    	}
    }

    /**
     * @Route("/subscribe_newsletter_form", name="api_sor_subscribe_newsletter_form")
     * @Template()
     */
    public function subscribeNewsletterFormAction()
    {
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();



        $iDossier = $oRequest->get('dossier', null);
        $iAssociation = $oRequest->get('association', null);
        /** @var Association $oAssociation */
        $oAssociation = null;

        if(null!==$iAssociation) {
            $oAssociation = $oEm->find('VJPWoningDossierDBBundle:Association', $iAssociation);
        }


        $oForm = $this->createForm(new NewsletterSubscribeForm());

        if(null===$oAssociation) {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "nok")));
        }

        /** @var DossierManager $oDossierManager */
        $oDossierManager = $this->get('vjp.woningdossier.manager.dossier');


        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sName = $oForm->get('name')->getData();
            $sLastName = $oForm->get('lastname')->getData();
            $sEmail = $oForm->get('email')->getData();

            $oLead = $oEm->getRepository('VJPWoningDossierDBBundle:Lead')->findOneBy(array(
                'association' => $oAssociation->getId(),
                'email' => strtolower($sEmail)
            ));
            if(null==$oLead) {
                $oLead = new Lead();
                $oLead->setAssociation($oAssociation);
                $oLead->setEmail($sEmail);
                $oEm->persist($oLead);
            }

            $oLead->setName($sLastName);
            $oLead->setFirstname($sName);

            /** @var LeadAction $oLeadActionNewsletter */
            $oLeadActionNewsletter = null;
            /** @var LeadAction $oLeadAction */
            foreach($oLead->getActions() as $oLeadAction) {
                if($oLeadAction->getActionType() == LeadAction::ACTION_NEWSLETTER) {
                    $oLeadActionNewsletter = $oLeadAction;
                    break;
                }
            }

            if(null === $oLeadActionNewsletter) {
                $oLeadActionNewsletter = new LeadAction();
                $oLeadActionNewsletter->setActionType(LeadAction::ACTION_NEWSLETTER);
                $oLeadActionNewsletter->setLead($oLead);
                $oEm->persist($oLeadActionNewsletter);
                $oEm->flush();
                $oEm->refresh($oLeadActionNewsletter);
                if($oLead->getAssociation()->getDossierAccess()) {
                    try {
                        $oDossierManager->newLeadAction($oLeadAction);
                    } catch(\Exception $oEx) {

                    }
                }
            }

            return new Response(json_encode(array('result' => "ok")));
        } else {
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'all_errors' => $oForm->getErrorsAsString(), 'result' => "nok")));
        }
    }

    /**
     * @Route("/finance-submit", name="api_finance_submit")
     * @Template()
     */

    public function financeSubmitAction()
    {
        
        $oRequest = $this->getRequest();
        /** @var EntityManager $oEm */
        $oEm = $this->getDoctrine()->getManager();

        $oForm = $this->createForm(new FinanceForm(), array());

        $oForm->handleRequest($oRequest);
        if($oForm->isValid()) {
            $sCar = $oForm->get('car')->getData();
            $sFinance = $oForm->get('finance')->getData();
            $sPhone = $oForm->get('phone')->getData();
            $sName = $oForm->get('name')->getData();
            $sEmail = $oForm->get('email')->getData();
            $sNetto = $oForm->get('netto')->getData();
            $sNettoPartner = $oForm->get('nettopartner')->getData();
            $sRent = $oForm->get('rent')->getData();
            $sLicense = $oForm->get('license')->getData();

            if (strlen($sPhone) > 9)
            {

            $bodymessage = '<body style="background:#FFFFFF; min-height:1000px; font-family: Helvetica, sans-serif; font-size:14px; margin:0"
alink="#FF0000" link="#FF0000" bgcolor="#000000" yahoo="fix"> 
     
    <!--PAGE WRAPPER-->
    <div id="body_style" style="padding:0px"> 
         
        <!-- PAGE LAYOUT -->
        <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center" style="padding-left:0px; border: 1px solid #f2f2f2;" font-size:14px;">
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
                        <tr>
                            <td>
                                 <table cellpadding="0" cellspacing="0" border="0" align="center" width="600px">
                                    <tr>
                                    <td width="100"><img src="http://www.autoservicehaarlem.nl/images/logo-autoservicehaarlem.png" style="padding-top: 40px; height: 100px; width: 114px;"></td>
                                    <td width="500"><h1 style="color:#4b4b4b; padding-top:36px; padding-left:15px;">AANVRAAG FINANCIERING</h1></td>
                                    </tr>
                                </table>
                                <table style="margin:0 auto; padding:30px 0;" width="600px" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td style="padding-left:0px; padding-top:0; line-height:200%;" valign="top" width="200px" height="100%"> 
                                            <ul style="list-style:none; display:inline; font-weight: bold; padding:0; color: #434343">
                                                <li style="margin-left: 0px !important;">Naam:</li>
                                                <li style="margin-left: 0px !important;">Email:</li>
                                                <li style="margin-left: 0px !important;">Telefoonnummer:</li>
                                                <li style="margin-left: 0px !important;">Kenteken:</li>
                                                <li style="margin-left: 0px !important;">Auto:</li>
                                                <li style="margin-left: 0px !important;">Te financieren bedrag:</li>
                                                <li style="margin-left: 0px !important;">Netto inkomen:</li>
                                                <li style="margin-left: 0px !important;">Netto inkomen partner:</li>
                                                <li style="margin-left: 0px !important;">Huur of hypotheeklasten:</li>
                                            </ul> 
                                        </td>
                                        <td style="padding-top:0; line-height:2em;" height="100%" valign="top"> 
                                            <ul style="list-style:none; display:inline; padding:0; color: #373737">
                                                <li style="margin-left: 0px !important;">'.$sName.'</li>
                                                <li style="margin-left: 0px !important;">'.$sEmail.'</li>
                                                <li style="margin-left: 0px !important;">'.$sPhone.'</li>
                                                <li style="margin-left: 0px !important;">'.$sLicense.'</li>
                                                <li style="margin-left: 0px !important;">'.$sCar.'</li>
                                                <li style="margin-left: 0px !important;">€'.$sFinance.'</li>
                                                <li style="margin-left: 0px !important;">€'.$sNetto.'</li>
                                                <li style="margin-left: 0px !important;">€'.$sNettoPartner.'</li>
                                                <li style="margin-left: 0px !important;">€'.$sRent.'</li>
                                            </ul> 
                                        </td>

                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#f2f2f2">
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" border="0" width="600px" style="margin:0 auto; line-height:1.8em; padding-bottom: 25px;">
                                    <tr>
                                        <td width="450px" style="padding-left: 0px; padding-top: 25px">
                                            <h4 style="margin:0;">Auto Service Haarlem</h4>
                                            <ul style="list-style:none; display:inline; padding:0; margin:0;">
                                                <li style="margin-left: 0px !important;">Munterslaan 2</li>
                                                <li style="margin-left: 0px !important;">2014 KW Haarlem</li>
                                            <br>
                                                <li style="margin-left: 0px !important;">T. 023 792 00 02</li>
                                                <li style="margin-left: 0px !important;">T. 023 792 00 03</li>
                                                <li style="margin-left: 0px !important;">info@autoservicehaarlem.nl</li>
                                                <li style="margin-left: 0px !important;">www.autoservicehaarlem.nl</li>
                                            </ul>

                                        </td>
                                        <td width="450px" style="text-align:right;" valign="top">
                                            <h4 style="margin:0; padding-top:25px;">Openingstijden</h4>
                                            <ul style="list-style:none; display:inline; padding:0; margin:0;">
                                                <li>Ma t/m vrij - Van 9.00 tot 18:00 uur</li>
                                                <li>Zaterdags - Van 9.00 tot 17.00 uur</li>
                                                <li>Zondag - Gesloten</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table> 
    </div> <!--end wrapper-->
</body>';

            $message = \Swift_Message::newInstance()
                ->setSubject('Aanvraag Financiering Auto Service Haarlem')
                ->setFrom('info@autoservicehaarlem.nl')
                ->setTo($sEmail)
                ->setBcc(array('info@autoservicehaarlem.nl', 'info@autoplannederland.nl', 'info@merqwaardig.com'))
                ->setBody($bodymessage, 'text/html');
                  
                
            ;
            $this->get('mailer')->send($message);



            return new Response(json_encode(array('result' => "ok", 'name' => $sName, 'email' => $sEmail, 'phone' => $sPhone, 'license' => $sLicense, 'car' => $sCar,'finance' => $sFinance, 'netto' => $sNetto, 'nettoPartner' => $sNettoPartner, 'rent' => $sRent)));
            }
            else{
                return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'result' => "phonenok")));
            }   
        } 
        else {
             $sPhone = $oForm->get('phone')->getData();
            return new Response(json_encode(array('errors'=> $this->getErrorMessages($oForm), 'phone' => $sPhone, 'result' => "nok")));
        }
    }
    
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
    	$errors = array();
    
    	if ($form->count()>0) {
    		foreach ($form->all() as $child) {
    			if (!$child->isValid()) {
    				$errors[$child->getName()] = $this->getErrorMessages($child);
    			}
    		}
    	} else {
    		foreach ($form->getErrors() as $key => $error) {
    			$errors[] = $error->getMessage();
    		}
    	}
    
    	return $errors;
    }
}
