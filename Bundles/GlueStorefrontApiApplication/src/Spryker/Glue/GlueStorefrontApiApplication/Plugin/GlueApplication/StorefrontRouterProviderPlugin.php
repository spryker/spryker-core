<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ApiApplicationEndpointProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class StorefrontRouterProviderPlugin extends AbstractPlugin implements ApiApplicationEndpointProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Gets route collection from current Glue Storefront API Application.
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        $routeCollection = new RouteCollection();
        $routeCollection = $this->addCustomRoutesCollection($routeCollection);
        $routeCollection = $this->addResourceRoutesCollection($routeCollection);

        return $routeCollection;
    }

    /**
     * {@inheritDoc}
     * - Returns name of Glue Storefront API Application.
     *
     * @api
     *
     * @return string
     */
    public function getApiApplicationName(): string
    {
        return 'GlueStorefrontApiApplication';
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addCustomRoutesCollection(RouteCollection $routeCollection): RouteCollection
    {
        $routeProviderPlugins = $this->getFactory()->getRouteProviderPlugins();
        foreach ($routeProviderPlugins as $routeProviderPlugin) {
            $routeCollection = $routeProviderPlugin->addRoutes($routeCollection);
        }

        return $routeCollection;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addResourceRoutesCollection(RouteCollection $routeCollection): RouteCollection
    {
        $resourcePlugins = $this->getFactory()->getResourcePlugins();
        $resourceRouteBuilder = $this->getFactory()->createResourceRouteBuilder();

        foreach ($resourcePlugins as $resourcePlugin) {
            $resourceRoutes = $resourceRouteBuilder->buildRoutes($resourcePlugin);
            $routeCollection = $this->addRoutesToCollection($routeCollection, $resourceRoutes);
        }

        return $routeCollection;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     * @param array<string, \Symfony\Component\Routing\Route> $resourceRoutes
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addRoutesToCollection(RouteCollection $routeCollection, array $resourceRoutes): RouteCollection
    {
        foreach ($resourceRoutes as $routeKey => $route) {
            $routeCollection->add($routeKey, $route);
        }

        return $routeCollection;
    }
}
