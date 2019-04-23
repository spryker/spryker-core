<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Generator;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Router as SymfonyRouter;

class UrlGenerator extends SymfonyUrlGenerator implements RouterEnhancerAwareInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    protected $request;

    /**
     * @var \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected $routerEnhancerPlugins;

    /**
     * @param \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[] $routerEnhancerPlugins
     *
     * @return void
     */
    public function setRouterEnhancerPlugins(array $routerEnhancerPlugins): void
    {
        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generate($name, $parameters = [], $referenceType = SymfonyRouter::ABSOLUTE_PATH)
    {
        $route = $this->routes->get($name);

        if (!$route) {
            throw new RouteNotFoundException(sprintf('Could not find a route by name "%s" in the current route collection.', $name));
        }

        $parameters = $this->convertParameters($parameters, $route);

        $generatedUrl = parent::generate($name, $parameters, $referenceType);

        foreach (array_reverse($this->routerEnhancerPlugins) as $routerEnhancerPlugin) {
            $generatedUrl = $routerEnhancerPlugin->afterGenerate($generatedUrl, $this->getContext());
        }

        return $generatedUrl;
    }

    /**
     * @param array $parameters
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return array
     */
    protected function convertParameters(array $parameters, Route $route)
    {
        $converters = $route->getOption('_converters');
        foreach ($parameters as $name => $value) {
            if (!isset($converters[$name]) || !isset($parameters[$name])) {
                continue;
            }

            $parameters[$name] = $converters[$name]($value, $this->getRequest());
        }

        return $parameters;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }
}
