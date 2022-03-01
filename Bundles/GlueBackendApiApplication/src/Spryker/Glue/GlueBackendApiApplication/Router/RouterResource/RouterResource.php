<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Router\RouterResource;

use Symfony\Component\Routing\RouteCollection;

class RouterResource implements RouterResourceInterface
{
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @var array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected $routeProviderPlugins = [];

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     * @param array<\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProviderPlugins
     */
    public function __construct(RouteCollection $routeCollection, array $routeProviderPlugins)
    {
        $this->routeCollection = $routeCollection;
        $this->routeProviderPlugins = $routeProviderPlugins;
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        foreach ($this->routeProviderPlugins as $routeProviderPlugin) {
            $this->routeCollection = $routeProviderPlugin->addRoutes($this->routeCollection);
        }

        return $this->routeCollection;
    }
}
