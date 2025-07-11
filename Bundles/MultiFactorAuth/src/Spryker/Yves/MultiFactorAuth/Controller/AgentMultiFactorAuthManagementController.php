<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Agent\MultiFactorAuthAgentRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class AgentMultiFactorAuthManagementController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_NAME_AGENT_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_AGENT_OVERVIEW = 'agent/overview';

    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_NAME_LOGIN
     *
     * @var string
     */
    protected const ROUTE_NAME_LOGIN = 'login';

    /**
     * @var string
     */
    protected const MESSAGE_ACTIVATION_SUCCESS = 'multi_factor_auth.activation.success';

    /**
     * @var string
     */
    protected const MESSAGE_DEACTIVATION_SUCCESS = 'multi_factor_auth.deactivation.success';

    /**
     * @var string
     */
    protected const MESSAGE_ACTIVATION_ERROR = 'multi_factor_auth.activation.error';

    /**
     * @var string
     */
    protected const MESSAGE_DEACTIVATION_ERROR = 'multi_factor_auth.deactivation.error';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_COLLECTION = 'multiFactorAuthCollection';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ENABLED = 'multi_factor_auth_enabled';

    /**
     * @var string
     */
    protected const ACTIVATE_FORM_NAME = 'activateForm';

    /**
     * @var string
     */
    protected const DEACTIVATE_FORM_NAME = 'deactivateForm';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ACTIVATE = 'multi_factor_auth_activate';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_DEACTIVATE = 'multi_factor_auth_deactivate';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ACTIVATE = 'csrf_token_activate';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_DEACTIVATE = 'csrf_token_deactivate';

    /**
     * @var string
     */
    protected const PARAM_REQUEST_TOKEN = '_csrf_token';

    /**
     * @var string
     */
    protected const MESSAGE_MULTI_FACTOR_AUTH_INVALID_TOKEN = 'multi_factor_auth.invalid_csrf_token';

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    protected CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->csrfTokenManager = $this->getFactory()->getCsrfTokenManager();
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setAgentMultiFactorAuthAction()
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === false) {
            return $this->redirectResponseInternal(static::ROUTE_NAME_LOGIN);
        }

        if ($this->getFactory()->createAgentMultiFactorAuthReader()->isAgentMultiFactorAuthPluginsAvailable() !== true) {
            return $this->redirectResponseInternal(static::ROUTE_NAME_AGENT_OVERVIEW);
        }

        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($this->getFactory()->getAgentClient()->getAgent());

        $userMultiFactorAuthTypesCollection = $this->getFactory()
            ->createAgentMultiFactorAuthReader()
            ->getAvailableAgentMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        return $this->view(
            [
                static::MULTI_FACTOR_AUTH_COLLECTION => $userMultiFactorAuthTypesCollection,
                static::CSRF_TOKEN_ACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_ACTIVATE)->getValue(),
                static::CSRF_TOKEN_DEACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_DEACTIVATE)->getValue(),
            ],
            [],
            '@MultiFactorAuth/views/user-set-up-page/set-up-page.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAgentMultiFactorAuthAction(Request $request): Response
    {
        if (!$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_ACTIVATE)) {
            $this->addErrorMessage(static::MESSAGE_MULTI_FACTOR_AUTH_INVALID_TOKEN);

            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $this->getAgent($request);

        /** @var string|null $multiFactorAuthType */
        $multiFactorAuthType = $request->query->get(MultiFactorAuthTransfer::TYPE);

        if ($this->isRequestCorrupted($request, static::ACTIVATE_FORM_NAME) || $this->isCodeBlocked($userTransfer, $multiFactorAuthType)) {
            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::MESSAGE_ACTIVATION_ERROR);

            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $this->getFactory()->createAgentMultiFactorAuthActivator()->activate($request, $userTransfer);
        $this->addSuccessMessage(static::MESSAGE_ACTIVATION_SUCCESS);

        return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deactivateAgentMultiFactorAuthAction(Request $request): Response
    {
        if (!$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_DEACTIVATE)) {
            $this->addErrorMessage(static::MESSAGE_MULTI_FACTOR_AUTH_INVALID_TOKEN);

            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $this->getAgent($request);

        /** @var string|null $multiFactorAuthType */
        $multiFactorAuthType = $request->query->get(MultiFactorAuthTransfer::TYPE);

        if ($this->isRequestCorrupted($request, static::DEACTIVATE_FORM_NAME) || $this->isCodeBlocked($userTransfer, $multiFactorAuthType)) {
            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::MESSAGE_DEACTIVATION_ERROR);

            return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $this->getFactory()->createAgentMultiFactorAuthDeactivator()->deactivate($request, $userTransfer);
        $this->addSuccessMessage(static::MESSAGE_DEACTIVATION_SUCCESS);

        return $this->redirectResponseInternal(MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $formName
     *
     * @return bool
     */
    protected function isRequestCorrupted(Request $request, string $formName): bool
    {
        return $this->getParameterFromRequest($request, static::MULTI_FACTOR_AUTH_ENABLED, $formName) === null;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string|null $multiFactorAuthType
     *
     * @return bool
     */
    protected function isCodeBlocked(UserTransfer $userTransfer, ?string $multiFactorAuthType): bool
    {
        $multiFactorValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())
            ->setType($multiFactorAuthType)
            ->setUser($userTransfer);

        return $this->getClient()->validateAgentMultiFactorAuthStatus($multiFactorValidationRequestTransfer)->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED;
    }

    /**
     * @param string|null $token
     * @param string $tokenId
     *
     * @return bool
     */
    protected function isCsrfTokenValid(?string $token, string $tokenId): bool
    {
        if (!$token) {
            return false;
        }

        $csrfToken = new CsrfToken($tokenId, $token);

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function getAgent(Request $request, ?string $formName = null): ?UserTransfer
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === true) {
            return $this->getFactory()->getAgentClient()->getAgent();
        }

        $email = $this->getParameterFromRequest($request, UserTransfer::EMAIL, $formName);
        $userTransfer = (new UserTransfer())->setUsername($email);

        return $this->getFactory()->getAgentClient()->findAgentByUsername($userTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $parameter
     * @param string|null $formName
     *
     * @return mixed
     */
    protected function getParameterFromRequest(Request $request, string $parameter, ?string $formName = null): mixed
    {
        return $this->getFactory()->createRequestReader()->get($request, $parameter, $formName);
    }
}
