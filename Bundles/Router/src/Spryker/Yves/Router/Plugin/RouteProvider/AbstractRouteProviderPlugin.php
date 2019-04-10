<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouteProvider;

use Spryker\Shared\Router\Route\Route;
use Spryker\Shared\RouterExtension\Dependency\Plugin\RouteProviderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\ClassResolver\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

abstract class AbstractRouteProviderPlugin extends AbstractPlugin implements RouteProviderPluginInterface
{
    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filterChain;

    /**
     * @param string $path
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    protected function buildGetRoute(string $path, string $moduleName, string $controllerName, string $actionName = 'index'): Route
    {
        return $this->buildRoute($path, $moduleName, $controllerName, $actionName)
            ->method(Request::METHOD_GET);
    }

    /**
     * @param string $path
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param bool $parseJsonBody
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    protected function buildPostRoute(string $path, string $moduleName, string $controllerName, string $actionName = 'index', bool $parseJsonBody = false): Route
    {
        return $this->buildRoute($path, $moduleName, $controllerName, $actionName, $parseJsonBody)
            ->method(Request::METHOD_POST);
    }

    /**
     * @param string $path
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param bool $parseJsonBody
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    protected function buildRoute(string $path, string $moduleName, string $controllerName, string $actionName = 'indexAction', bool $parseJsonBody = false): Route
    {
        $route = new Route($path);

        $moduleNameControllerAction = new BundleControllerAction($moduleName, $controllerName, $actionName);
        $controllerResolver = new ControllerResolver();
        $controller = $controllerResolver->resolve($moduleNameControllerAction);
        $filterChain = $this->getFilterChain();

        $template = sprintf(
            '%s/%s/%s',
            $moduleName,
            $filterChain->filter(str_replace('Controller', '', $controllerName)),
            $filterChain->filter(str_replace('Action', '', $actionName))
        );

        $route->setDefault('_controller', [get_class($controller), $actionName]);
        $route->setDefault('_template', $template);

        if ($parseJsonBody) {
            $this->addJsonParsing($route);
        }

        return $route;
    }

    /**
     * @param \Spryker\Shared\Router\Route\Route $route
     *
     * @return void
     */
    private function addJsonParsing(Route $route)
    {
        $route->before(function (Request $request) {
            $isJson = (strpos($request->headers->get('Content-Type'), 'application/json') === 0);

            if ($isJson) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getFilterChain(): FilterChain
    {
        if ($this->filterChain === null) {
            $this->filterChain = new FilterChain();
            $this->filterChain
                ->attach(new CamelCaseToDash())
                ->attach(new StringToLower());
        }

        return $this->filterChain;
    }
}
