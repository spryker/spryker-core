<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication\RouteProvider;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use Spryker\Glue\Kernel\BundleControllerAction;
use Spryker\Glue\Kernel\ClassResolver\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

abstract class AbstractRouteProviderPlugin extends AbstractPlugin
{
    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createGetRoute(string $path, string $resourceName): Route
    {
        $defaults = array_filter([
            '_resourceName' => $resourceName,
            '_method' => 'get',
        ]);

        return (new Route($path, $defaults))->setMethods(Request::METHOD_GET);
    }

    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createGetCollectionRoute(string $path, string $resourceName): Route
    {
        $defaults = array_filter([
            '_resourceName' => $resourceName,
            '_method' => 'getCollection',
        ]);

        return (new Route($path, $defaults))->setMethods(Request::METHOD_GET);
    }

    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createPostRoute(string $path, string $resourceName): Route
    {
        $defaults = array_filter([
            '_resourceName' => $resourceName,
            '_method' => 'post',
        ]);

        return (new Route($path, $defaults))->setMethods(Request::METHOD_POST);
    }

    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createPatchRoute(string $path, string $resourceName): Route
    {
        $defaults = array_filter([
            '_resourceName' => $resourceName,
            '_method' => 'patch',
        ]);

        return (new Route($path, $defaults))->setMethods(Request::METHOD_PATCH);
    }

    /**
     * @param string $path
     * @param string $resourceName
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createDeleteRoute(string $path, string $resourceName): Route
    {
        $defaults = array_filter([
            '_resourceName' => $resourceName,
            '_method' => 'delete',
        ]);

        return (new Route($path, $defaults))->setMethods(Request::METHOD_DELETE);
    }

    /**
     * @param string $path
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param string $method
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function createRouteToController(
        string $path,
        string $moduleName,
        string $controllerName,
        string $actionName,
        string $method
    ): Route {
        $route = new Route($path);

        if (preg_match('/Action$/', $actionName) === 0) {
            $actionName .= 'Action';
        }
        $moduleNameControllerAction = new BundleControllerAction($moduleName, $controllerName, $actionName);
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->resolve($moduleNameControllerAction);

        $route->setDefault('_controller', [get_class($controller), $actionName]);
        $route->setDefault('_method', $method);

        return $route;
    }
}
