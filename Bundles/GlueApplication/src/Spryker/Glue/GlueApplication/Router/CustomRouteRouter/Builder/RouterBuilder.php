<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Builder;

use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\Router;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterInterface;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterResource\RouterResource;
use Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterResource\RouterResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\RouteCollection;

class RouterBuilder implements RouterBuilderInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface>
     */
    protected array $routesProviderPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface> $routesProviderPlugins
     */
    public function __construct(array $routesProviderPlugins)
    {
        $this->routesProviderPlugins = $routesProviderPlugins;
    }

    /**
     * @param string $apiApplicationName
     *
     * @return \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterInterface|null
     */
    public function buildRouter(string $apiApplicationName): ?RouterInterface
    {
        $routesProviderPlugin = $this->findRoutesProvider($apiApplicationName);
        if (!$routesProviderPlugin) {
            return null;
        }

        return new Router(
            $this->createClosureLoader(),
            $this->createRouterResource($routesProviderPlugin->getRouteProviderPlugins()),
            $routesProviderPlugin->getConfiguration()['options'] ?? [],
        );
    }

    /**
     * @return \Symfony\Component\Config\Loader\LoaderInterface
     */
    public function createClosureLoader(): LoaderInterface
    {
        return new ClosureLoader();
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProviderPlugins
     *
     * @return \Spryker\Glue\GlueApplication\Router\CustomRouteRouter\RouterResource\RouterResourceInterface
     */
    public function createRouterResource(array $routeProviderPlugins): RouterResourceInterface
    {
        return new RouterResource(
            $this->createRouteCollection(),
            $routeProviderPlugins,
        );
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function createRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    /**
     * @param string $apiApplicationName
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RoutesProviderPluginInterface|null
     */
    protected function findRoutesProvider(string $apiApplicationName): ?RoutesProviderPluginInterface
    {
        foreach ($this->routesProviderPlugins as $routesProviderPlugin) {
            if ($routesProviderPlugin->getApplicationName() !== $apiApplicationName) {
                continue;
            }

            return $routesProviderPlugin;
        }

        return null;
    }
}
