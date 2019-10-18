<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router;

use Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\Generator\CompiledUrlGenerator;
use Symfony\Component\Routing\Generator\ConfigurableRequirementsInterface;
use Symfony\Component\Routing\Generator\Dumper\CompiledUrlGeneratorDumper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\CompiledUrlMatcher;
use Symfony\Component\Routing\Matcher\Dumper\CompiledUrlMatcherDumper;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface, WarmableInterface
{
    /**
     * @var \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[]
     */
    protected $routerEnhancerPlugins;

    /**
     * @var \Symfony\Component\Config\ConfigCacheFactoryInterface|null
     */
    protected $configCacheFactory;

    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param mixed $resource
     * @param \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface[] $routerEnhancerPlugins
     * @param array $options
     */
    public function __construct(LoaderInterface $loader, $resource, array $routerEnhancerPlugins, array $options = [])
    {
        parent::__construct($loader, $resource, $options);

        $this->routerEnhancerPlugins = $routerEnhancerPlugins;
    }

    /**
     * @return \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface|\Symfony\Component\Routing\Matcher\UrlMatcherInterface|null
     */
    public function getMatcher()
    {
        if ($this->matcher !== null) {
            return $this->matcher;
        }

        $compiled = is_a($this->options['matcher_class'], CompiledUrlMatcher::class, true);

        if ($this->options['cache_dir'] === null || $this->options['matcher_cache_class'] === null) {
            $routes = $this->getRouteCollection();
            if ($compiled) {
                $routes = (new CompiledUrlMatcherDumper($routes))->getCompiledRoutes();
            }
            $this->matcher = new $this->options['matcher_class']($routes, $this->context);
            $this->matcher = $this->setRouterEnhancerPluginsToMatcher($this->matcher);

            return $this->matcher;
        }

        $cache = $this->getConfigCacheFactory()->cache(
            $this->options['cache_dir'] . '/' . $this->options['matcher_cache_class'] . '.php',
            function (ConfigCacheInterface $cache) {
                $dumper = $this->getMatcherDumperInstance();

                $options = [
                    'class' => $this->options['matcher_cache_class'],
                    'base_class' => $this->options['matcher_base_class'],
                ];

                $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
            }
        );

        if ($compiled) {
            $this->matcher = new $this->options['matcher_class'](require $cache->getPath(), $this->context);
            $this->matcher = $this->setRouterEnhancerPluginsToMatcher($this->matcher);

            return $this->matcher;
        }

        if (!class_exists($this->options['matcher_cache_class'], false)) {
            require_once $cache->getPath();
        }

        $this->matcher = new $this->options['matcher_cache_class']($this->context);

        $this->matcher = $this->setRouterEnhancerPluginsToMatcher($this->matcher);

        return $this->matcher;
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
     * @return \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerAwareInterface|\Symfony\Component\Routing\Generator\UrlGeneratorInterface|null
     */
    public function getGenerator()
    {
        if ($this->generator !== null) {
            return $this->generator;
        }

        $compiled = is_a($this->options['generator_class'], CompiledUrlGenerator::class, true);

        if ($this->options['cache_dir'] === null || $this->options['generator_cache_class'] === null) {
            $routes = $this->getRouteCollection();
            if ($compiled) {
                $routes = (new CompiledUrlGeneratorDumper($routes))->getCompiledRoutes();
            }
            $this->generator = new $this->options['generator_class']($routes, $this->context, $this->logger, $this->defaultLocale);
        } else {
            $cache = $this->getConfigCacheFactory()->cache(
                $this->options['cache_dir'] . '/' . $this->options['generator_cache_class'] . '.php',
                function (ConfigCacheInterface $cache) {
                    $dumper = $this->getGeneratorDumperInstance();

                    $options = [
                        'class' => $this->options['generator_cache_class'],
                        'base_class' => $this->options['generator_base_class'],
                    ];

                    $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
                }
            );

            if ($compiled) {
                $this->generator = new $this->options['generator_class'](require $cache->getPath(), $this->context, $this->logger);
            } else {
                if (!class_exists($this->options['generator_cache_class'], false)) {
                    require_once $cache->getPath();
                }

                $this->generator = new $this->options['generator_cache_class']($this->context, $this->logger, $this->defaultLocale);
            }
        }

        if ($this->generator instanceof ConfigurableRequirementsInterface) {
            $this->generator->setStrictRequirements($this->options['strict_requirements']);
        }

        $this->generator = $this->setRouterEnhancerPluginsToGenerator($this->generator);

        return $this->generator;
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

    /**
     * @param string $cacheDir
     *
     * @return void
     */
    public function warmUp($cacheDir): void
    {
        $this->getGenerator();
        $this->getMatcher();
    }
}
