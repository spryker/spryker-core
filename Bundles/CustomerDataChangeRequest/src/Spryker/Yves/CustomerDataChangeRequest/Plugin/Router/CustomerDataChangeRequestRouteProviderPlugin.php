<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CustomerDataChangeRequestRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const CUSTOMER_DATA_CHANGE_REQUEST_NAME_CHANGE_EMAIL = 'customerDataChangeRequest/changeEmail';

    /**
     * @var string
     */
    protected const CUSTOMER_DATA_CHANGE_REQUEST_ROUTE_CHANGE_EMAIL = '/customer-data-change-request/change-email';

    /**
     * @var string
     */
    protected const CUSTOMER_DATA_CHANGE_REQUEST_ACTION_CHANGE_EMAIL = 'changeEmailAction';

    /**
     * @var string
     */
    protected const CUSTOMER_DATA_CHANGE_REQUEST_MODULE = 'CustomerDataChangeRequest';

    /**
     * @var string
     */
    protected const CUSTOMER_DATA_CHANGE_CONTROLLER = 'Page';

    /**
     * {@inheritDoc}
     * - Adds customer data change request routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addChangeEmailRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addChangeEmailRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::CUSTOMER_DATA_CHANGE_REQUEST_ROUTE_CHANGE_EMAIL,
            static::CUSTOMER_DATA_CHANGE_REQUEST_MODULE,
            static::CUSTOMER_DATA_CHANGE_CONTROLLER,
            static::CUSTOMER_DATA_CHANGE_REQUEST_ACTION_CHANGE_EMAIL,
        );
        $routeCollection->add(static::CUSTOMER_DATA_CHANGE_REQUEST_NAME_CHANGE_EMAIL, $route);

        return $routeCollection;
    }
}
