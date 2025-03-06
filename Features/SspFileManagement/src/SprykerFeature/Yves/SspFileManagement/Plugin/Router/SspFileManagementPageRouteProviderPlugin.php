<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class SspFileManagementPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    protected const ROUTE_NAME_SSP_FILE_MANAGEMENT = 'ssp-file-management';

    /**
     * @var string
     */
    protected const ROUTE_NAME_SSP_FILE_MANAGEMENT_DOWNLOAD = 'ssp-file-management/download';

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
        $routeCollection = $this->addSspFileManagementRoute($routeCollection);
        $routeCollection = $this->addSspFileManagementDownloadRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspFileManagement\Controller\SspFileManagementViewController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection*
     */
    protected function addSspFileManagementRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/ssp-file-management', 'SspFileManagement', 'SspFileManagementView', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_SSP_FILE_MANAGEMENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerFeature\Yves\SspFileManagement\Controller\SspFileManagementDownloadController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection*
     */
    protected function addSspFileManagementDownloadRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/ssp-file-management/download', 'SspFileManagement', 'SspFileManagementDownload', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_SSP_FILE_MANAGEMENT_DOWNLOAD, $route);

        return $routeCollection;
    }
}
