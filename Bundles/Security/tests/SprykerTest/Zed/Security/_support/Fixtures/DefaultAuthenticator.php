<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Security\Fixtures;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class DefaultAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_LOGIN_FORM = 'loginForm';

    /**
     * @var string
     */
    protected const PARAMETER_USERNAME = 'username';

    /**
     * @var string
     */
    protected const PARAMETER_PASSWORD = 'password';

    /**
     * @var string
     */
    protected const MESSAGE = 'message';

    /**
     * @var string
     */
    protected const AUTHENTICATION_REQUIRED_MESSAGE = 'Authentication Required';

    /**
     * @var string
     */
    protected const FAKE_LOGIN_URI = 'http://localhost/login';

    /**
     * @var string
     */
    protected const FAKE_HOMEPAGE_URI = 'http://localhost/';

    /**
     * @var string
     */
    protected const FAKE_ADMIN_URI = 'http://localhost/admin';

    /**
     * @var string
     */
    protected const USER_IDENTIFIER = 'user';

    /**
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\Passport $passport
     * @param string $firewallName
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new PostAuthenticationToken(
            $passport->getUser(),
            $firewallName,
            $passport->getUser()->getRoles(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Security\Http\Authenticator\Passport\Passport
     */
    public function authenticate(Request $request): Passport
    {
        $data = $request->request->all(static::PARAMETER_LOGIN_FORM);
        $userProvider = new DefaultUserProvider();

        return new Passport(
            new UserBadge(
                $data[static::PARAMETER_USERNAME],
                function (string $userEmail) use ($userProvider) {
                    return $userProvider->loadUserByIdentifier($userEmail);
                },
            ),
            new PasswordCredentials($data[static::PARAMETER_PASSWORD]),
            [],
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->request->has(static::PARAMETER_LOGIN_FORM);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param string $firewallName
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($token->getUserIdentifier() === static::USER_IDENTIFIER) {
            return new RedirectResponse(static::FAKE_HOMEPAGE_URI);
        }

        return new RedirectResponse(static::FAKE_ADMIN_URI);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            static::MESSAGE => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, 403);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(static::FAKE_LOGIN_URI);
    }
}
