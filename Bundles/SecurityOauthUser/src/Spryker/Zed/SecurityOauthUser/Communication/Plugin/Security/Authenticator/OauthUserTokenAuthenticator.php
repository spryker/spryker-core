<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Plugin\Security\Authenticator;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

/**
 * @method \Spryker\Zed\SecurityOauthUser\Communication\SecurityOauthUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthUser\SecurityOauthUserConfig getConfig()
 * @method \Spryker\Zed\SecurityOauthUser\Business\SecurityOauthUserFacadeInterface getFacade()
 */
class OauthUserTokenAuthenticator extends AbstractPlugin implements AuthenticatorInterface
{
    /**
     * {@inheritDoc}
     * - Creates a post authentication guard token for the given user.
     *
     * @api
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param string $providerKey
     *
     * @return \Symfony\Component\Security\Guard\Token\GuardTokenInterface
     */
    public function createAuthenticatedToken(UserInterface $user, string $providerKey)
    {
        return $this->getFactory()->createPostAuthenticationGuardToken($user, $providerKey);
    }

    /**
     * {@inheritDoc}
     * - Checks if the authenticator support the given request.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === SecurityOauthUserConfig::ROUTE_NAME_OAUTH_USER_LOGIN
            && $request->query->get(SecurityOauthUserConfig::REQUEST_PARAMETER_AUTHENTICATION_CODE)
            && $request->query->get(SecurityOauthUserConfig::REQUEST_PARAMETER_AUTHENTICATION_STATE);
    }

    /**
     * {@inheritDoc}
     * - Gets the authentication credentials from the request.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|mixed|void
     */
    public function getCredentials(Request $request)
    {
        $resourceOwnerTransfer = $this->getFactory()->createResourceOwnerReader()->getResourceOwner($request);

        return [
            'token' => $resourceOwnerTransfer ? $resourceOwnerTransfer->getEmail() : null,
        ];
    }

    /**
     * {@inheritDoc}
     * - Gets UserInterface object based on the credentials.
     *
     * @api
     *
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials || !$credentials['token']) {
            return null;
        }

        return $userProvider->loadUserByUsername($credentials['token']);
    }

    /**
     * {@inheritDoc}
     * - Checks if the credentials are valid.
     *
     * @api
     *
     * @param mixed $credentials
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $credentials['token'] === $user->getUsername();
    }

    /**
     * {@inheritDoc}
     * - Authorizes the Oauth user after successful authentication.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $providerKey
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return $this->getFactory()
            ->createOauthUserAuthenticationSuccessHandler()
            ->onAuthenticationSuccess($request, $token);
    }

    /**
     * {@inheritDoc}
     * - Redirects Oauth user to the login page on authentication failure.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->getFactory()
            ->createOauthUserAuthenticationFailureHandler()
            ->onAuthenticationFailure($request, $exception);
    }

    /**
     * {@inheritDoc}
     * - Returns a response that directs the user to authenticate.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->getConfig()->getUrlLogin());
    }

    /**
     * {@inheritDoc}
     * - Checks if this method support remember me cookies.
     *
     * @api
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
