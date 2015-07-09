<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Customer\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AjaxAuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{

    /**
     * @param    Request $request
     * @param    TokenInterface $token
     *
     * @return    Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $array = ['success' => true]; // data to return via JSON
        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param    Request $request
     * @param    AuthenticationException $exception
     *
     * @return    Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $array = ['success' => false, 'message' => $exception->getMessage()]; // data to return via JSON
        $response = new Response(json_encode($array));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
