<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationEndpointProviderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class BackendRouterProviderPlugin extends AbstractPlugin implements ApiApplicationEndpointProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets route collection from current Glue Backend API Application.
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $routeProviderPlugins = $this->getFactory()->getRouteProviderPlugins();
        foreach ($routeProviderPlugins as $routeProviderPlugin) {
            $routeCollection = $routeProviderPlugin->addRoutes($routeCollection);
        }

        return $routeCollection;
    }

    /**
     * {@inheritDoc}
     * - Returns name of Glue Backend API Application.
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string
    {
        return 'GlueBackendApiApplication';
    }
}
