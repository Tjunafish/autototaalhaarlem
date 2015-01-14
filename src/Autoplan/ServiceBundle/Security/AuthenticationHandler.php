<?php
/**
 * Created by PhpStorm.
 * User: simonsabelis
 * Date: 3/13/14
 * Time: 12:27 PM
 */

namespace Autoplan\ServiceBundle\Security;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler extends ContainerAware implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface{

    /**
     * @var Router $oRouting
     */
    public $oRouting;

    public  function __construct(Container $oContainer) {
        $this->container = $oContainer;
        $this->oRouting = $oContainer->get('router');
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new Response(json_encode(array('login_status' => "success", 'redirect_url' => $this->oRouting->generate('admin_dashboard'))));
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response(json_encode(array('login_status' => "invalid", "exception" => $exception->getMessage())));
    }
}