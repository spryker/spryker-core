<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Agent\MultiFactorAuthAgentRouteProviderPlugin;
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

    /**
     * @var string
     */
    protected const ROUTE_PARAM = '_route';

    /**
     * @var string
     */
    protected const REQUEST_STACK = 'request_stack';

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
        $this->addParameter(static::PARAMETER_CONFIGURATIONS, []);

        if ($this->assertCustomerMultiFactorAuthShouldBeSkipped() && $this->assertAgentMultiFactorAuthShouldBeSkipped()) {
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
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    protected function findAgent(): ?UserTransfer
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === false) {
            return null;
        }

        return $this->getFactory()->getAgentClient()->getAgent();
    }

    /**
     * @return bool
     */
    protected function isSetUpPage(): bool
    {
        return in_array(
            $this->getGlobalContainer()->get(static::REQUEST_STACK)->getCurrentRequest()->get(static::ROUTE_PARAM),
            [
                MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH,
                MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH,
            ],
        );
    }

    /**
     * @return bool
     */
    protected function assertCustomerMultiFactorAuthShouldBeSkipped(): bool
    {
        $customerTransfer = $this->findCustomer();

        if ($customerTransfer === null) {
            return false;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);

        return $this->getClient()->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequiredOrFail() && !$this->isSetUpPage();
    }

    /**
     * @return bool
     */
    protected function assertAgentMultiFactorAuthShouldBeSkipped(): bool
    {
        $userTransfer = $this->findAgent();

        if ($userTransfer === null) {
            return false;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);

        return $this->getClient()->validateAgentMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequiredOrFail() && !$this->isSetUpPage();
    }
}
