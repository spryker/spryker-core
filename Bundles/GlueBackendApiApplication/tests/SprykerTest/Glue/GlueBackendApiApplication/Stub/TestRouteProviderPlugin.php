<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication\Stub;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TestRouteProviderPlugin extends AbstractPlugin implements RouteProviderPluginInterface
{
    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = (new Route('/get'))
            ->setDefault(
                '_controller',
                [
                    ResourceController::class,
                    'getCollectionAction',
                ],
            )
            ->setMethods(Request::METHOD_GET);
        $routeCollection->add('get', $route);

        $postRoute = (new Route('/post'))
            ->setDefault(
                '_controller',
                [
                    ResourceController::class,
                    'getCollectionAction',
                ],
            )
            ->setMethods(Request::METHOD_POST);
        $routeCollection->add('post', $postRoute);

        return $routeCollection;
    }
}
