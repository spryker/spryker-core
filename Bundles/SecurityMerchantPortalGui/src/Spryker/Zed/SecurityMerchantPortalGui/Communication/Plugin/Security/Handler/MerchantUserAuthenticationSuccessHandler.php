<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\Handler;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @method \Spryker\Zed\SecurityMerchantPortalGui\Communication\SecurityMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityMerchantPortalGui\SecurityMerchantPortalGuiConfig getConfig()
 */
class MerchantUserAuthenticationSuccessHandler extends AbstractPlugin implements AuthenticationSuccessHandlerInterface
{
    use TargetPathTrait;

    /**
     * @uses \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\Security\MerchantUserSecurityPlugin::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'MerchantUser';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PRE_AUTH = 'ACCESS_MODE_PRE_AUTH';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @uses {@link \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController::getEnabledTypesAction()}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_NAME = 'multi-factor-auth-merchant-portal:merchant-user:get-enabled-types';

    /**
     * Handles Agent Merchant Portal authentication success with Multi-Factor Authentication support.
     *
     * Flow scenarios:
     * 1. Multi-Factor Authentication Required: Has `ACCESS_MODE_PRE_AUTH` role → Store user in session, return JSON to open Multi-Factor Authentication modal.
     * 2. Standard Auth: No Multi-Factor Authentication required → Set current user, redirect based on Multi-Factor Authentication plugin availability.
     * 3. Multi-Factor Authentication Completed: Full access granted → Standard redirect to target URL
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        /** @var \Spryker\Zed\SecurityMerchantPortalGui\Communication\Security\MerchantUserInterface $user */
        $user = $token->getUser();
        $merchantUserTransfer = $user->getMerchantUserTransfer();

        if (in_array(static::ACCESS_MODE_PRE_AUTH, $token->getRoleNames())) {
            $this->getFactory()->getSessionClient()->set(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY, $merchantUserTransfer->getUserOrFail()->getUsername());

            return $this->createOpenModalResponse($request);
        }

        $this->executeOnAuthenticationSuccess($merchantUserTransfer);

        if ($this->getFactory()->getMerchantUserMultiFactorAuthenticationHandlerPlugins() !== []) {
            return $this->createRedirectResponse($request);
        }

        return new RedirectResponse($this->getTargetUrl($request));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(MerchantUserTransfer $merchantUserTransfer): void
    {
        $this->getFactory()->getMerchantUserFacade()->authorizeMerchantUser($merchantUserTransfer);

        $this->getFactory()->createAuditLogger()->addSuccessfulLoginAuditLog();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getTargetUrl(Request $request): string
    {
        return $this->getTargetPath($request->getSession(), static::SECURITY_FIREWALL_NAME) ?? $this->getConfig()->getDefaultTargetPath();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createOpenModalResponse(Request $request): JsonResponse
    {
        /** @var string|null $formSelector */
        $formSelector = $request->request->get('form_selector') ?? null;

        $zedUIFormRequestActionTransfer = (new ZedUiFormRequestActionTransfer())
            ->setUrl($this->generateRoute())
            ->setIsLogin(true)
            ->setFormSelector($formSelector);

        return new JsonResponse($this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionOpenModal($zedUIFormRequestActionTransfer)
            ->createResponse()
            ->toArray());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createRedirectResponse(Request $request): JsonResponse
    {
        return new JsonResponse($this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionRedirect($this->getTargetUrl($request))
            ->createResponse()
            ->toArray());
    }

    /**
     * @return string
     */
    protected function generateRoute(): string
    {
        $router = $this->getFactory()->getRouterFacade()->getMerchantPortalChainRouter();

        return $router->generate(static::MULTI_FACTOR_AUTH_ROUTE_NAME);
    }
}
