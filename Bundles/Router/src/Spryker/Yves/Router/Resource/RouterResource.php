<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Resource;

use Spryker\Shared\Router\Resource\ResourceInterface;
use Spryker\Shared\Router\Route\RouteCollection;

class RouterResource implements ResourceInterface
{
    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @var \Spryker\Shared\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[]
     */
    protected $routeProvider = [];

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     * @param \Spryker\Shared\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface[] $routeProvider
     */
    public function __construct(RouteCollection $routeCollection, array $routeProvider)
    {
        $this->routeCollection = $routeCollection;
        $this->routeProvider = $routeProvider;
    }

    /**
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function __invoke(): RouteCollection
    {
        foreach ($this->routeProvider as $routeProviderPlugin) {
            $this->routeCollection = $routeProviderPlugin->addRoutes($this->routeCollection);
        }

        return $this->routeCollection;
    }
}
