<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication\Authenticator;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class SystemUserTokenAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    /**
     * @var string
     */
    protected const TOKEN = 'token';

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
    protected const EXCEPTION_MESSAGE_NO_API_TOKEN_PROVIDED = 'No API token provided';

    /**
     * @var \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface
     */
    protected SecuritySystemUserToUserFacadeInterface $userFacade;

    /**
     * @var \Symfony\Component\Security\Core\User\UserProviderInterface
     */
    protected UserProviderInterface $userProvider;

    /**
     * @param \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface $userFacade
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     */
    public function __construct(
        SecuritySystemUserToUserFacadeInterface $userFacade,
        UserProviderInterface $userProvider
    ) {
        $this->userFacade = $userFacade;
        $this->userProvider = $userProvider;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException
     *
     * @return \Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport
     */
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $token = $request->headers->get(strtolower(SecuritySystemUserConfig::AUTH_TOKEN));

        if (!$token) {
            throw new CustomUserMessageAuthenticationException(static::EXCEPTION_MESSAGE_NO_API_TOKEN_PROVIDED);
        }

        return new SelfValidatingPassport(
            new UserBadge($token, function ($userToken) {
                return $this->userProvider->loadUserByIdentifier($userToken);
            }),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has(strtolower(SecuritySystemUserConfig::AUTH_TOKEN));
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
        /** @var \Spryker\Zed\SecuritySystemUser\Communication\Security\SystemUserInterface $user */
        $user = $token->getUser();

        $this->userFacade->setCurrentUser(
            (new UserTransfer())
                ->setUsername($user->getUserIdentifier())
                ->setIsSystemUser(true),
        );

        return null;
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
    public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
    {
        $data = [
            static::MESSAGE => static::AUTHENTICATION_REQUIRED_MESSAGE,
        ];

        return new JsonResponse($data, 401);
    }

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
     * @deprecated since Symfony 5.4, use {@link createToken()} instead.
     * Method exists only for PHPStan due to its fatal errors during analyzing files.
     *
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface $passport
     * @param string $firewallName
     *
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface /** @phpstan-ignore-line */
    {
        return $this->createToken($passport, $firewallName);
    }
}
