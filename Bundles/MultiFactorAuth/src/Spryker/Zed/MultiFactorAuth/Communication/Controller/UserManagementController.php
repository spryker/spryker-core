<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
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
class UserManagementController extends AbstractController
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
     * @uses {@link \Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->csrfTokenManager = $this->getFactory()->getCsrfTokenManager();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function setUpAction()
    {
        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === false) {
            return $this->redirectResponse(static::LOGIN_PATH);
        }

        $userMultiFactorAuthPlugins = $this->getFactory()->getUserMultiFactorAuthPlugins();
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($this->getUser());

        $userMultiFactorAuthTypesCollection = $this->getFacade()
            ->getAvailableUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer, $userMultiFactorAuthPlugins);

        return $this->renderView($this->getSetUpTemplatePath(), [
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
        $userTransfer = $this->getUser();
        $multiFactorAuthType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE);

        if ($this->isRequestInvalid($request, $userTransfer, $multiFactorAuthType, static::CSRF_TOKEN_ID_ACTIVATE, static::ACTIVATE_FORM_NAME)) {
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
        $userTransfer = $this->getUser();
        $multiFactorAuthType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE);

        if ($this->isRequestInvalid($request, $userTransfer, $multiFactorAuthType, static::CSRF_TOKEN_ID_DEACTIVATE, static::DEACTIVATE_FORM_NAME)) {
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

        $multiFactorValidationResponseTransfer = $this->getFacade()
            ->validateUserMultiFactorAuthStatus(
                $multiFactorValidationRequestTransfer,
                [MultiFactorAuthConstants::STATUS_ACTIVE, MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION],
            );

        return $multiFactorValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED;
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

    /**
     * @return string
     */
    protected function getSetUpTemplatePath(): string
    {
        return '@MultiFactorAuth/UserManagement/set-up.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUser(): UserTransfer
    {
        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === true) {
            return $this->getFactory()->getUserFacade()->getCurrentUser();
        }

        $username = $this->getFactory()->getSessionClient()->get(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY);

        if ($username === null) {
            return new UserTransfer();
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions((new UserConditionsTransfer())
                ->addUsername($username));

        return $this->getFactory()->getUserFacade()->getUserCollection($userCriteriaTransfer)->getUsers()->offsetGet(0);
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $multiFactorAuthType
     * @param string $csrfTokenId
     * @param string $formName
     *
     * @return bool
     */
    protected function isRequestInvalid(
        Request $request,
        UserTransfer $userTransfer,
        string $multiFactorAuthType,
        string $csrfTokenId,
        string $formName
    ): bool {
        return !$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), $csrfTokenId)
            || $this->isRequestCorrupted($request, $formName)
            || $this->isCodeBlocked($userTransfer, $multiFactorAuthType);
    }
}
