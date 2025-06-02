<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer\MultiFactorAuthCustomerRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class CustomerMultiFactorAuthManagementController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_CUSTOMER_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN
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
    public function setMultiFactorAuthAction()
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            return $this->redirectResponseInternal(static::ROUTE_NAME_LOGIN);
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (
            $this->getFactory()->createCustomerMultiFactorAuthReader()->isCustomerMultiFactorAuthPluginsAvailable() !== true ||
            $customerTransfer === null
        ) {
            return $this->redirectResponseInternal(static::ROUTE_NAME_CUSTOMER_OVERVIEW);
        }

        $customerMultiFactorAuthTypesCollection = $this->getFactory()
            ->createCustomerMultiFactorAuthReader()
            ->getAvailableCustomerMultiFactorAuthTypes($customerTransfer);

        return $this->view(
            [
                static::MULTI_FACTOR_AUTH_COLLECTION => $customerMultiFactorAuthTypesCollection,
                static::CSRF_TOKEN_ACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_ACTIVATE)->getValue(),
                static::CSRF_TOKEN_DEACTIVATE => $this->csrfTokenManager->getToken(static::CSRF_TOKEN_ID_DEACTIVATE)->getValue(),
            ],
            [],
            '@MultiFactorAuth/views/customer-set-up-page/set-up-page.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateCustomerMultiFactorAuthAction(Request $request): Response
    {
        if (!$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_ACTIVATE)) {
            $this->addErrorMessage(static::MESSAGE_MULTI_FACTOR_AUTH_INVALID_TOKEN);

            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $customerTransfer = $this->getCustomer($request);

        if ($this->isRequestCorrupted($request, static::ACTIVATE_FORM_NAME) || $this->isCodeBlocked($customerTransfer)) {
            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $multiFactorAuthType = $request->query->get(MultiFactorAuthTransfer::TYPE);

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::MESSAGE_ACTIVATION_ERROR);

            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $this->getFactory()->createCustomerMultiFactorAuthActivator()->activate($request, $customerTransfer);
        $this->addSuccessMessage(static::MESSAGE_ACTIVATION_SUCCESS);

        return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deactivateCustomerMultiFactorAuthAction(Request $request): Response
    {
        if (!$this->isCsrfTokenValid($this->getParameterFromRequest($request, static::PARAM_REQUEST_TOKEN), static::CSRF_TOKEN_ID_DEACTIVATE)) {
            $this->addErrorMessage(static::MESSAGE_MULTI_FACTOR_AUTH_INVALID_TOKEN);

            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $customerTransfer = $this->getCustomer($request);

        if ($this->isRequestCorrupted($request, static::DEACTIVATE_FORM_NAME) || $this->isCodeBlocked($customerTransfer)) {
            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $multiFactorAuthType = $request->query->get(MultiFactorAuthTransfer::TYPE);

        if ($multiFactorAuthType === null) {
            $this->addErrorMessage(static::MESSAGE_DEACTIVATION_ERROR);

            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $this->getFactory()->createCustomerMultiFactorAuthDeactivator()->deactivate($request, $customerTransfer);
        $this->addSuccessMessage(static::MESSAGE_DEACTIVATION_SUCCESS);

        return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isCodeBlocked(CustomerTransfer $customerTransfer): bool
    {
        $multiFactorValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())
            ->setCustomer($customerTransfer);

        return $this->getClient()->validateCustomerMultiFactorAuthStatus($multiFactorValidationRequestTransfer)->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED;
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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(Request $request, ?string $formName = null): CustomerTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            return $customerTransfer;
        }

        $email = $this->getParameterFromRequest($request, CustomerTransfer::EMAIL, $formName);
        $customerTransfer = (new CustomerTransfer())->setEmail($email);

        return $this->getFactory()->getCustomerClient()->getCustomerByEmail($customerTransfer);
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
