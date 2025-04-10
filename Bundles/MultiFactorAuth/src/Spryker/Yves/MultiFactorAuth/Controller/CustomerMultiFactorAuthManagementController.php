<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer\MultiFactorAuthCustomerRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class CustomerMultiFactorAuthManagementController extends AbstractCustomerMultiFactorAuthController
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
            [static::MULTI_FACTOR_AUTH_COLLECTION => $customerMultiFactorAuthTypesCollection],
            [],
            '@MultiFactorAuth/views/set-up-page/set-up-page.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateCustomerMultiFactorAuthAction(Request $request): Response
    {
        $customerTransfer = $this->getCustomer($request);

        if ($this->isRequestCorrupted($request, static::ACTIVATE_FORM_NAME) || $this->isCodeBlocked($customerTransfer)) {
            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $multiFactorAuthType = $request->query->get(static::TYPE);

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
        $customerTransfer = $this->getCustomer($request);

        if ($this->isRequestCorrupted($request, static::DEACTIVATE_FORM_NAME) || $this->isCodeBlocked($customerTransfer)) {
            return $this->redirectResponseInternal(MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH);
        }

        $multiFactorAuthType = $request->query->get(static::TYPE);

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
}
