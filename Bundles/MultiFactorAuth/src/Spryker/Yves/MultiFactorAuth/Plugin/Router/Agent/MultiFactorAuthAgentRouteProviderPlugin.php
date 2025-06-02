<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Plugin\Router\Agent;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MultiFactorAuthAgentRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_GET_USER_ENABLED_TYPES = 'multiFactorAuth/getUserEnabledTypes';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_SEND_USER_CODE = 'multiFactorAuth/sendUserCode';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_NAME_SET_MULTI_FACTOR_AUTH = 'agent/multiFactorAuth/set';

    /**
     * @var string
     */
    public const MULTI_FACTOR_AUTH_ROUTE_GET_USER_ENABLED_TYPES = '/multi-factor-auth/get-user-enabled-types';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_NAME_ACTIVATE_MULTI_FACTOR_AUTH = 'agent/multiFactorAuth/activate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_NAME_DEACTIVATE_MULTI_FACTOR_AUTH = 'agent/multiFactorAuth/deactivate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_SEND_USER_CODE = '/multi-factor-auth/send-user-code';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_SET_MULTI_FACTOR_AUTH = '/agent/multi-factor-auth/set';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_ACTIVATE_MULTI_FACTOR_AUTH = '/agent/multi-factor-auth/activate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ROUTE_DEACTIVATE_MULTI_FACTOR_AUTH = '/agent/multi-factor-auth/deactivate';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_GET_AGENT_ENABLED_TYPES = 'getAgentEnabledTypesAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_SEND_AGENT_CODE = 'sendAgentCodeAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_SET_MULTI_FACTOR_AUTH = 'setAgentMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_ACTIVATE_AGENT_MULTI_FACTOR_AUTH = 'activateAgentMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_ACTION_DEACTIVATE_AGENT_MULTI_FACTOR_AUTH = 'deactivateAgentMultiFactorAuthAction';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_MODULE = 'MultiFactorAuth';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_AGENT_FLOW_CONTROLLER = 'AgentMultiFactorAuthFlow';

    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_AGENT_MANAGEMENT_CONTROLLER = 'AgentMultiFactorAuthManagement';

    /**
     * {@inheritDoc}
     * - Adds multi-factor authentication agent routes to the RouteCollection.
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
            static::MULTI_FACTOR_AUTH_AGENT_MANAGEMENT_CONTROLLER,
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
            static::MULTI_FACTOR_AUTH_AGENT_MANAGEMENT_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_ACTIVATE_AGENT_MULTI_FACTOR_AUTH,
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
            static::MULTI_FACTOR_AUTH_AGENT_MANAGEMENT_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_DEACTIVATE_AGENT_MULTI_FACTOR_AUTH,
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
            static::MULTI_FACTOR_AUTH_ROUTE_GET_USER_ENABLED_TYPES,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_AGENT_FLOW_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_GET_AGENT_ENABLED_TYPES,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_GET_USER_ENABLED_TYPES, $route);

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
            static::MULTI_FACTOR_AUTH_ROUTE_SEND_USER_CODE,
            static::MULTI_FACTOR_AUTH_MODULE,
            static::MULTI_FACTOR_AUTH_AGENT_FLOW_CONTROLLER,
            static::MULTI_FACTOR_AUTH_ACTION_SEND_AGENT_CODE,
        );
        $routeCollection->add(static::MULTI_FACTOR_AUTH_NAME_SEND_USER_CODE, $route);

        return $routeCollection;
    }
}
