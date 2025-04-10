<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MultiFactorAuthCustomerRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_GET_CUSTOMER_ENABLED_TYPES = 'multiFactorAuth/getCustomerEnabledTypes';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_SEND_CUSTOMER_CODE = 'multiFactorAuth/sendCustomerCode';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH = 'multiFactorAuth/set';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_ROUTE_GET_CUSTOMER_ENABLED_TYPES = '/multi-factor-auth/get-customer-enabled-types';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_NAME_ACTIVATE_MULTI_FACTOR_AUTH = 'multiFactorAuth/activate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_NAME_DEACTIVATE_MULTI_FACTOR_AUTH = 'multiFactorAuth/deactivate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_SEND_CUSTOMER_CODE = '/multi-factor-auth/send-customer-code';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_SET_MULTI_FACTOR_AUTH = '/multi-factor-auth/set';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_ACTIVATE_MULTI_FACTOR_AUTH = '/multi-factor-auth/activate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_DEACTIVATE_MULTI_FACTOR_AUTH = '/multi-factor-auth/deactivate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_GET_CUSTOMER_ENABLED_TYPES = 'getCustomerEnabledTypesAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_SEND_CUSTOMER_CODE = 'sendCustomerCodeAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_SET_MULTI_FACTOR_AUTH = 'setMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_ACTIVATE_CUSTOMER_MULTI_FACTOR_AUTH = 'activateCustomerMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_DEACTIVATE_CUSTOMER_MULTI_FACTOR_AUTH = 'deactivateCustomerMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_MODULE = 'MultiFactorAuth';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_CUSTOMER_FLOW_CONTROLLER = 'CustomerMultiFactorAuthFlow';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_CUSTOMER_MANAGEMENT_CONTROLLER = 'CustomerMultiFactorAuthManagement';

    /**
     * {@inheritDoc}
     * - Adds multi-factor authentication customer routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSetMultiFactorAuthRoute($routeCollection);
        $routeCollection = $this->addActivateMultiFactorAuthRoute($routeCollection);
        $routeCollection = $this->addDeactivateMultiFactorAuthRoute($routeCollection);
        $routeCollection = $this->addGetEnabledTypesRoute($routeCollection);
        $routeCollection = $this->addSendCodeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSetMultiFactorAuthRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::MULTI_FACTOR_AUTH_ROUTE_SET_MULTI_FACTOR_AUTH,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_CUSTOMER_MANAGEMENT_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_SET_MULTI_FACTOR_AUTH,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addActivateMultiFactorAuthRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::MULTI_FACTOR_AUTH_ROUTE_ACTIVATE_MULTI_FACTOR_AUTH,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_CUSTOMER_MANAGEMENT_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_ACTIVATE_CUSTOMER_MULTI_FACTOR_AUTH,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_ACTIVATE_MULTI_FACTOR_AUTH, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addDeactivateMultiFactorAuthRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::MULTI_FACTOR_AUTH_ROUTE_DEACTIVATE_MULTI_FACTOR_AUTH,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_CUSTOMER_MANAGEMENT_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_DEACTIVATE_CUSTOMER_MULTI_FACTOR_AUTH,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_DEACTIVATE_MULTI_FACTOR_AUTH, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addGetEnabledTypesRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::MULTI_FACTOR_AUTH_ROUTE_GET_CUSTOMER_ENABLED_TYPES,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_CUSTOMER_FLOW_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_GET_CUSTOMER_ENABLED_TYPES,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_GET_CUSTOMER_ENABLED_TYPES, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSendCodeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::MULTI_FACTOR_AUTH_ROUTE_SEND_CUSTOMER_CODE,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_CUSTOMER_FLOW_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_SEND_CUSTOMER_CODE,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_SEND_CUSTOMER_CODE, $route);

        return $routeCollection;
    }
}
