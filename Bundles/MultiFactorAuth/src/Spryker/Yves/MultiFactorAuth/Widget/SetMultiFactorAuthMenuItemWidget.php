<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Agent\MultiFactorAuthAgentRouteProviderPlugin;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer\MultiFactorAuthCustomerRouteProviderPlugin;

/**
 * Use this widget to display the multi-factor authentication menu item in the customer profile navigation.
 *
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class SetMultiFactorAuthMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_SET_MULTI_FACTOR_AUTH = 'setMultiFactorAuth';

    /**
     * @var string
     */
    protected const PAGE_KEY_SET_AGENT_MULTI_FACTOR_AUTH = 'setAgentMultiFactorAuth';

    /**
     * @var string
     */
    protected const PARAMETER_SET_MULTI_FACTOR_AUTH_ROUTE_NAME = 'setMultiFactorAuthRouteName';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addIsVisibleParameter();
        $this->addIsActivePageParameter($activePage);
        $this->addSetMultiFactorAuthRouteNameParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SetMultiFactorAuthMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiFactorAuth/views/multi-factor-auth-menu-item/multi-factor-auth-menu-item.twig';
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $this->isWidgetVisible());
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addIsActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isSetMultiFactorAuthPageActive($activePage));
    }

    /**
     * @return void
     */
    protected function addSetMultiFactorAuthRouteNameParameter(): void
    {
        $routeName = $this->getFactory()->getCustomerClient()->isLoggedIn() ?
            MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH :
            MultiFactorAuthAgentRouteProviderPlugin::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH;

        $this->addParameter(static::PARAMETER_SET_MULTI_FACTOR_AUTH_ROUTE_NAME, $routeName);
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isSetMultiFactorAuthPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_SET_MULTI_FACTOR_AUTH || $activePage === static::PAGE_KEY_SET_AGENT_MULTI_FACTOR_AUTH;
    }

    /**
     * @return bool
     */
    protected function isWidgetVisible(): bool
    {
        return $this->assertCustomerMultiFactorAuthPluginsEnabled() || $this->assertAgentMultiFactorAuthPluginsEnabled();
    }

    /**
     * @return bool
     */
    protected function assertCustomerMultiFactorAuthPluginsEnabled(): bool
    {
        return $this->getFactory()->getCustomerClient()->isLoggedIn() && $this->getFactory()->getCustomerMultiFactorAuthPlugins() !== [];
    }

    /**
     * @return bool
     */
    protected function assertAgentMultiFactorAuthPluginsEnabled(): bool
    {
        return $this->getFactory()->getAgentClient()->isLoggedIn() && $this->getFactory()->getAgentMultiFactorAuthPlugins() !== [];
    }
}
