<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer\MultiFactorAuthCustomerRouteProviderPlugin;

/**
 * Manages and provides multi-factor authentication configurations and routes to the template.
 *
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClient getClient()
 */
class MultiFactorAuthHandlerWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_CONFIGURATIONS = 'configurations';

    /**
     * @var string
     */
    protected const PARAMETER_GET_CUSTOMER_ENABLED_TYPES_ROUTE_NAME = 'getCustomerEnabledTypesRouteName';

    public function __construct()
    {
        $this->addConfigurationsParameter();
        $this->addGetCustomerEnabledTypesRouteNameParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MultiFactorAuthHandlerWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiFactorAuth/views/multi-factor-auth-handler/multi-factor-auth-handler.twig';
    }

    /**
     * @return void
     */
    protected function addConfigurationsParameter(): void
    {
        $customerTransfer = $this->findCustomer();
        $this->addParameter(static::PARAMETER_CONFIGURATIONS, []);

        if ($customerTransfer === null) {
            return;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);

        if (
            $this->getClient()->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false && !$this->isSetUpPage()
        ) {
            return;
        }

        $this->addParameter(static::PARAMETER_CONFIGURATIONS, $this->getConfig()->getEnabledRoutesAndForms());
    }

    /**
     * @return void
     */
    protected function addGetCustomerEnabledTypesRouteNameParameter(): void
    {
        $this->addParameter(static::PARAMETER_GET_CUSTOMER_ENABLED_TYPES_ROUTE_NAME, MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_GET_CUSTOMER_ENABLED_TYPES);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomer(): ?CustomerTransfer
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn() === false) {
            return null;
        }

        return $this->getFactory()->getCustomerClient()->getCustomer();
    }

    /**
     * @return bool
     */
    protected function isSetUpPage(): bool
    {
        return $this->getGlobalContainer()->get('request_stack')->getCurrentRequest()->get('_route') === MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH;
    }
}
