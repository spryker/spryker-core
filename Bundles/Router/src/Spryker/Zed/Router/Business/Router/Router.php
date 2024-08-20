<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router;

use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\RouterConfig;
use Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface, WarmableInterface
{
    /**
     * @var string
     */
    protected const ROUTE_CACHE_PATH = '/url_generating_routes.php';

    /**
     * @var string
     */
    protected const CACHE_DIR_PATH_PLACEHOLDER = '%s%s';

    /**
     * @var string
     */
    protected const OPTION_CACHE_DIR = 'cache_dir';

    /**
     * @var array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface>
     */
    protected $routerEnhancerPlugins;

    /**
     * @var \Symfony\Component\Config\ConfigCacheFactoryInterface|null
     */
    protected $configCacheFactory;

    /**
     * @var \Spryker\Zed\Router\RouterConfig
     */
    protected RouterConfig $routerConfig;

    /**
     * @var array<string, mixed>|null
     */
    protected static ?array $cache = [];

    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param mixed $resource
     * @param \Spryker\Zed\Router\RouterConfig $routerConfig
     * @param array<\Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface> $routerEnhancerPlugins
     * @param array<string, mixed> $options
     */
    public function __construct(
        LoaderInterface $loader,
        $resource,
        RouterConfig $routerConfig,
        array $routerEnhancerPlugins,
        array $options = []
    ) {
        parent::__construct($loader, $resource, $options);

        $this->routerConfig = $routerConfig;
        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
    }

    /**
     * @return \Symfony\Component\Routing\Matcher\UrlMatcherInterface|\Symfony\Component\Routing\Matcher\RequestMatcherInterface
     */
    public function getMatcher(): UrlMatcherInterface|RequestMatcherInterface
    {
        if ($this->matcher !== null) {
            return $this->matcher;
        }

        /** @var \Symfony\Component\Routing\Matcher\UrlMatcherInterface $urlMatcher */
        $urlMatcher = parent::getMatcher();
        $this->matcher = $this->setRouterEnhancerPluginsToMatcher($urlMatcher);

        return $this->matcher;
    }

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        $cachePath = sprintf(static::CACHE_DIR_PATH_PLACEHOLDER, $this->options[static::OPTION_CACHE_DIR], static::ROUTE_CACHE_PATH);
        if (!$this->routerConfig->isRoutingCacheEnabled() || !file_exists($cachePath)) {
            return parent::getRouteCollection();
        }

        $routes = static::getSymfonyCompiledRoutes($cachePath);

        return $this->mapRouteDataArrayCollectionToRouteCollection($routes);
    }

    /**
     * @param array<mixed> $route
     *
     * @return \Spryker\Zed\Router\Business\Route\Route
     */
    protected function mapRouteDataArrayToRouteObject(array $route): Route
    {
        [, $defaults, , $pathProperties] = $route;
        [, $path] = array_pop($pathProperties);
        $routeObject = new Route($path);
        $routeObject->addDefaults($defaults);

        return $routeObject;
    }

    /**
     * @param array<mixed> $routes
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function mapRouteDataArrayCollectionToRouteCollection(array $routes): RouteCollection
    {
        $routeCollection = new RouteCollection();

        foreach ($routes as $name => $route) {
            $routeObject = $this->mapRouteDataArrayToRouteObject($route);
            $routeCollection->add($name, $routeObject);
        }

        return $routeCollection;
    }

    /**
     * @param string $path
     *
     * @return array<mixed>
     */
    public static function getSymfonyCompiledRoutes(string $path): array
    {
        if (!isset(static::$cache[$path])) {
            static::$cache[$path] = require $path;
        }

        return static::$cache[$path] ??= require $path;
    }

    /**
     * @param \Symfony\Component\Routing\Matcher\UrlMatcherInterface $matcher
     *
     * @return \Symfony\Component\Routing\Matcher\UrlMatcherInterface
     */
    protected function setRouterEnhancerPluginsToMatcher(UrlMatcherInterface $matcher): UrlMatcherInterface
    {
        if ($matcher instanceof RouterEnhancerAwareInterface) {
            $matcher->setRouterEnhancerPlugins($this->routerEnhancerPlugins);
        }

        return $matcher;
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    public function getGenerator(): UrlGeneratorInterface
    {
        if ($this->generator !== null) {
            return $this->generator;
        }

        $this->generator = $this->setRouterEnhancerPluginsToGenerator(parent::getGenerator());

        return $this->generator;
    }

    /**
     * @param string $cacheDir
     *
     * @return array<string>
     */
    public function warmUp(string $cacheDir): array
    {
        $this->getGenerator();
        $this->getMatcher();

        return [];
    }

    /**
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $generator
     *
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function setRouterEnhancerPluginsToGenerator(UrlGeneratorInterface $generator): UrlGeneratorInterface
    {
        if ($generator instanceof RouterEnhancerAwareInterface) {
            $generator->setRouterEnhancerPlugins($this->routerEnhancerPlugins);
        }

        return $generator;
    }

    /**
     * Provides the ConfigCache factory implementation, falling back to a
     * default implementation if necessary.
     *
     * @return \Symfony\Component\Config\ConfigCacheFactoryInterface
     */
    protected function getConfigCacheFactory()
    {
        if ($this->configCacheFactory === null) {
            $this->configCacheFactory = new ConfigCacheFactory($this->options['debug']);
        }

        return $this->configCacheFactory;
    }
}
