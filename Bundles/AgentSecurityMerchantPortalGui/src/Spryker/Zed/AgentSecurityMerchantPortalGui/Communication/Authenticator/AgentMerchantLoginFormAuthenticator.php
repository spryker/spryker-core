<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Authenticator;

use Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge\MultiFactorAuthBadge;
use Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Form\AgentMerchantLoginForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AgentMerchantLoginFormAuthenticator implements AuthenticatorInterface, AuthenticationEntryPointInterface
{
    /**
     * @var string
     */
    protected const AGENT_SECURITY_MERCHANT_PORTAL_GUI_REQUEST = 'agent-security-merchant-portal-gui';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PRE_AUTH = 'ACCESS_MODE_PRE_AUTH';

    /**
     * @uses \Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants::CODE_BLOCKED
     *
     * @var int
     */
    protected const CODE_BLOCKED = 1;

    /**
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $agentMerchantUserProvider
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface $authenticationSuccessHandler
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface $authenticationFailureHandler
     * @param \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge\MultiFactorAuthBadge $multiFactorAuthBadge
     */
    public function __construct(
        protected AgentSecurityMerchantPortalGuiConfig $agentSecurityMerchantPortalGuiConfig,
        protected UserProviderInterface $agentMerchantUserProvider,
        protected AuthenticationSuccessHandlerInterface $authenticationSuccessHandler,
        protected AuthenticationFailureHandlerInterface $authenticationFailureHandler,
        protected MultiFactorAuthBadge $multiFactorAuthBadge
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Security\Http\Authenticator\Passport\Passport
     */
    public function authenticate(Request $request): Passport
    {
        $data = $request->request->all(static::AGENT_SECURITY_MERCHANT_PORTAL_GUI_REQUEST);

        /** @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Security\AgentMerchantUserInterface $user */
        $user = $this->agentMerchantUserProvider->loadUserByIdentifier($data[AgentMerchantLoginForm::FIELD_USERNAME]);
        $badges = [$this->multiFactorAuthBadge->enable($user->getUserTransfer())];

        return new Passport(
            new UserBadge($data[AgentMerchantLoginForm::FIELD_USERNAME], function () use ($user) {
                return $user;
            }),
            new PasswordCredentials($data[AgentMerchantLoginForm::FIELD_PASSWORD]),
            $badges,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->request->has(static::AGENT_SECURITY_MERCHANT_PORTAL_GUI_REQUEST);
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
        return $this->authenticationSuccessHandler->onAuthenticationSuccess($request, $token);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return $this->authenticationFailureHandler->onAuthenticationFailure($request, $exception);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException|null $authException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($this->agentSecurityMerchantPortalGuiConfig->getUrlLogin());
    }

    /**
     * If the user requires Multi-Factor Authentication, we assign a temporary 'ACCESS_MODE_PRE_AUTH' role.
     * This special role grants just enough access to complete the Multi-Factor Authentication verification
     * but prevents access to the rest of the application. Once Multi-Factor Authentication is successful,
     * a new token with the user's full roles will be created.
     *
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
            $this->assertUserIsPreAuthenticated($passport) ? [static::ACCESS_MODE_PRE_AUTH] : $passport->getUser()->getRoles(),
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

    /**
     * @param \Symfony\Component\Security\Http\Authenticator\Passport\Passport $passport
     *
     * @return bool
     */
    protected function assertUserIsPreAuthenticated(Passport $passport): bool
    {
        /** @var \Spryker\Zed\AgentSecurityMerchantPortalGui\Communication\Badge\MultiFactorAuthBadge $multiFactorAuthBadge */
        $multiFactorAuthBadge = $passport->getBadge(MultiFactorAuthBadge::class);

        return $multiFactorAuthBadge->getIsRequired() === true || $multiFactorAuthBadge->getStatus() === static::CODE_BLOCKED;
    }
}
