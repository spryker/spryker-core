<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SspServiceManagementPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_MANAGEMENT_SERVICE_POINT_WIDGET_CONTENT = 'ssp-service-management/service-point-widget-content';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_MANAGEMENT_SERVICE_POINT_SEARCH = 'ssp-service-management/service-point-widget/search';

    /**
     * @var string
     */
    public const ROUTE_NAME_CUSTOMER_SERVICE_LIST = 'customer/ssp-service';

    /**
     * @var string
     */
    public const ROUTE_NAME_SSP_SERVICE_UPDATE_TIME = 'ssp-service/update-service-time';

    /**
     * @var string
     */
    protected const PATTERN_CUSTOMER_SERVICE_LIST = '/customer/ssp-service';

    /**
     * @var string
     */
    protected const PATTERN_UPDATE_SERVICE_TIME = '/ssp-service/update-service-time';

    /**
     * {@inheritDoc}
     * - Adds routes to the route collection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addSspServiceManagementServicePointWidgetContentRoute($routeCollection);
        $routeCollection = $this->addSspServiceManagementServicePointSearchRoute($routeCollection);
        $routeCollection = $this->addSspServiceManagementCustomerServiceListRoute($routeCollection);
        $routeCollection = $this->addSspServiceManagementUpdateServiceTimeRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspServiceManagement\Controller\ServicePointWidgetContentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceManagementServicePointWidgetContentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/ssp-service-management/service-point-widget-content',
            'SspServiceManagement',
            'ServicePointWidgetContent',
        );

        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_MANAGEMENT_SERVICE_POINT_WIDGET_CONTENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspServiceManagement\Controller\ServicePointSearchController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceManagementServicePointSearchRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/ssp-service-management/service-point-widget/search', 'SspServiceManagement', 'ServicePointSearch');
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_MANAGEMENT_SERVICE_POINT_SEARCH, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspServiceManagement\Controller\SspServiceController::listAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceManagementCustomerServiceListRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_CUSTOMER_SERVICE_LIST,
            'SspServiceManagement',
            'SspService',
            'listAction',
        );
        $route = $route->setMethods(['GET']);
        $routeCollection->add(static::ROUTE_NAME_CUSTOMER_SERVICE_LIST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspServiceManagement\Controller\SspServiceController::updateServiceTimeAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addSspServiceManagementUpdateServiceTimeRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            static::PATTERN_UPDATE_SERVICE_TIME,
            'SspServiceManagement',
            'SspService',
            'updateServiceTimeAction',
        );
        $routeCollection->add(static::ROUTE_NAME_SSP_SERVICE_UPDATE_TIME, $route);

        return $routeCollection;
    }
}
