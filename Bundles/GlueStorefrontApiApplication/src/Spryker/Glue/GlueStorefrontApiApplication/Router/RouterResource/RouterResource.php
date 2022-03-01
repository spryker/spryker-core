<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Router\RouterResource;

use Symfony\Component\Routing\RouteCollection;

class RouterResource implements RouterResourceInterface
{
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected RouteCollection $routeCollection;

    /**
     * @var array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    protected $routeProvider = [];

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     * @param array<\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface> $routeProvider
     */
    public function __construct(RouteCollection $routeCollection, array $routeProvider)
    {
        $this->routeCollection = $routeCollection;
        $this->routeProvider = $routeProvider;
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        foreach ($this->routeProvider as $routeProviderPlugin) {
            $this->routeCollection = $routeProviderPlugin->addRoutes($this->routeCollection);
        }

        return $this->routeCollection;
    }
}
