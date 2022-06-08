<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Stub;

use Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Symfony\Component\Routing\RouteCollection;

class TestRouteProviderPlugin extends AbstractRouteProviderPlugin implements RouteProviderPluginInterface
{
    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->createGetRoute('/get', 'store');
        $routeCollection->add('get', $route);

        $postRoute = $this->createPostRoute('/post', 'store');
        $routeCollection->add('post', $postRoute);

        return $routeCollection;
    }
}
