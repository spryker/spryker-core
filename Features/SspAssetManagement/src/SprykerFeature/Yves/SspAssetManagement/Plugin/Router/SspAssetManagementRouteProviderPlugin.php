<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SspAssetManagementRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    protected const ROUTE_SSP_ASSET_MANAGEMENT_WIDGET_CONTENT = 'ssp-asset-management/asset-widget-content';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addAssetWidgetContentRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addAssetWidgetContentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/ssp-asset-management/asset-widget-content', 'SspAssetManagement', 'AssetWidgetContent', 'indexAction');
        $routeCollection->add(static::ROUTE_SSP_ASSET_MANAGEMENT_WIDGET_CONTENT, $route);

        return $routeCollection;
    }
}
