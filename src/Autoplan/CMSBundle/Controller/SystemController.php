<?php
/**
 * Created by JetBrains PhpStorm.
 * User: simonsabelis
 * Date: 4/17/13
 * Time: 15:15
 * To change this template use File | Settings | File Templates.
 */

namespace Autoplan\CMSBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SystemController
 * @package Autoplan\CMSBundle\Controller
 */
class SystemController extends Controller{

    /**
     * @Route("/login", name="admin_login")
     * @Template()
     */
    public function loginAction() {

        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="admin_login_check")
     * @Template()
     */
    public function loginCheckAction() {

    }

    /**
     * @Route("/logout", name="admin_logout")
     * @Template()
     */
    public function logoutAction() {

    }

    /**
     * @Route("/", name="admin_dashboard")
     * @Template()
     */
    public function dashboardAction() {

        return $this->redirect($this->generateUrl('admin_cars'));
        $oEm = $this->getDoctrine()->getManager();
        
        return array(
            'menu' => 'dashboard'
        );
    }

    /**
     * @Route("/pdf_ash_footer", name="admin_pdf_ash_footer")
     * @Template()
     */
    public function ashPdfFooterAction() {
        return $this->render('AutoplanCMSBundle:Car:carPdfASHFooter.html.twig');
    }

    /**
     * @Route("/pdf_autoplan_footer", name="admin_pdf_autoplan_footer")
     * @Template()
     */
    public function autoplanPdfFooterAction() {
        return $this->render('AutoplanCMSBundle:Car:carPdfAutoplanFooter.html.twig');

    }


}