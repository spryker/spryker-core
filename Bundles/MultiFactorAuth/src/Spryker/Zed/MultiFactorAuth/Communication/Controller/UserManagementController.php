<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class UserManagementController extends AbstractUserMultiFactorAuthController
{
    /**
     * @var string
     */
    protected const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var string
     */
    protected const ACTIVATION_SUCCESS_MESSAGE = 'The multi-factor authentication has been activated.';

    /**
     * @var string
     */
    protected const DEACTIVATION_SUCCESS_MESSAGE = 'The multi-factor authentication has been deactivated.';

    /**
     * @var string
     */
    protected const ACTIVATION_ERROR_MESSAGE = 'The multi-factor authentication could not be activated.';

    /**
     * @var string
     */
    protected const DEACTIVATION_ERROR_MESSAGE = 'The multi-factor authentication could not be deactivated.';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_COLLECTION = 'multiFactorAuthCollection';

    /**
     * @var string
     */
    protected const URL_REDIRECT_SET_UP_PAGE = '/multi-factor-auth/user-management/set-up';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ACTIVATE = 'multi_factor_auth_user_activate';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_DEACTIVATE = 'multi_factor_auth_user_deactivate';

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
    protected const ACTIVATE_FORM_NAME = 'activateForm';

    /**
     * @var string
     */
    protected const DEACTIVATE_FORM_NAME = 'deactivateForm';

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    protected CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     *
     * @var string
     */
    protected const LOGIN_PATH = '/security-gui/login';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ENABLED = 'multi_factor_auth_enabled';

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->csrfTokenManager = $this->getFactory()->getCsrfTokenManager();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function setUpAction(Request $request)
    {
        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === false) {
            return $this->redirectResponse(static::LOGIN_PATH);
        }

        $userMultiFactorAuthPlugins = $this->getFactory()->getUserMultiFactorAuthPlugins();
        $userTransfer = $this->getUser($request);

        $userMultiFactorAuthTypesCollection = $this->getFacade()
            ->getAvailableUserMultiFactorAuthTypes($userTransfer, $userMultiFactorAuthPlugins);

        return $this->viewResponse([
            static::MULTI_FACTOR_AUTH_COLLECTION => $userMultiFactorAuthTypesCollection,
            static::CSRF_TOKEN_ACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_ACTIVATE)->getValue(),
            static::CSRF_TOKEN_DEACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_DEACTIVATE)->getValue(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $userTransfer = $this->getUser($request);
        $multiFactorAuthType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE);

        if (
            !$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_ACTIVATE)
            || $this->isRequestCorrupted($request, static::ACTIVATE_FORM_NAME)
            || $this->isCodeBlocked($userTransfer, $multiFactorAuthType)
        ) {
            $this->addErrorMessage(static::ACTIVATION_ERROR_MESSAGE);

            return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
        }

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::ACTIVATION_ERROR_MESSAGE);

            return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
        }

        $this->getFactory()->createUserMultiFactorAuthActivator()->activate($request, $userTransfer);
        $this->addSuccessMessage(static::ACTIVATION_SUCCESS_MESSAGE);

        return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $userTransfer = $this->getUser($request);
        $multiFactorAuthType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE);

        if (
            !$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_DEACTIVATE)
            || $this->isRequestCorrupted($request, static::DEACTIVATE_FORM_NAME)
            || $this->isCodeBlocked($userTransfer, $multiFactorAuthType)
        ) {
            $this->addErrorMessage(static::DEACTIVATION_ERROR_MESSAGE);

            return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
        }

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::DEACTIVATION_ERROR_MESSAGE);

            return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
        }

        $this->getFactory()->createUserMultiFactorAuthDeactivator()->deactivate($request, $userTransfer);
        $this->addSuccessMessage(static::DEACTIVATION_SUCCESS_MESSAGE);

        return $this->redirectResponse(static::URL_REDIRECT_SET_UP_PAGE);
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

        return $multiFactorValidationResponseTransfer = $this->getFacade()->validateUserMultiFactorAuthStatus($multiFactorValidationRequestTransfer, [MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION])->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED;
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
     * @param string $formName
     *
     * @return bool
     */
    protected function isRequestCorrupted(Request $request, string $formName): bool
    {
        return $this->getParameterFromRequest($request, static::MULTI_FACTOR_AUTH_ENABLED, $formName) === null;
    }
}
