<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler\Communication\Plugin\Application;

use ReflectionClass;
use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Router\Business\Loader\ClosureLoader;
use Spryker\Zed\Router\Business\Route\Route;
use Spryker\Zed\Router\Business\Route\RouteCollection;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\Router;
use Spryker\Zed\Router\Business\Router\RouterInterface;
use Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin;
use Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\ProfilerExtension;
use Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Bundle\WebProfilerBundle\Controller\RouterController;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\EventListener\ProfilerListener;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Stopwatch\Stopwatch;
use Twig\Environment;
use Twig\Profiler\Profile;

/**
 * @method \Spryker\Zed\WebProfiler\Communication\WebProfilerCommunicationFactory getFactory()
 * @method \Spryker\Zed\WebProfiler\WebProfilerConfig getConfig()
 */
class WebProfilerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    public const SERVICE_STOPWATCH = 'stopwatch';
    public const SERVICE_LOGGER = 'logger';
    public const SERVICE_PROFILER = 'profiler';
    public const SERVICE_TWIG = 'twig';
    public const SERVICE_REQUEST = 'request';
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_STOPWATCH, function () {
            return new Stopwatch();
        });

        $container->extend(EventDispatcherApplicationPlugin::SERVICE_DISPATCHER, function (EventDispatcher $dispatcher, ContainerInterface $container) {
            $dispatcher = new TraceableEventDispatcher($dispatcher, $container->get(static::SERVICE_STOPWATCH), $container->get(static::SERVICE_LOGGER));

            return $dispatcher;
        });

        $container->set(static::SERVICE_PROFILER, function (Container $container) {
            $profilerStorage = new FileProfilerStorage('file:' . $this->getConfig()->getProfilerCacheDirectory());
            $profiler = new Profiler($profilerStorage);

            foreach ($this->getFactory()->getDataCollectorPlugins() as $dataCollectorPlugin) {
                $profiler->add($dataCollectorPlugin->getDataCollector($container));
            }

            return $profiler;
        });

        $container->extend(TwigApplicationPlugin::SERVICE_TWIG, function (Environment $twig, ContainerInterface $container) {
            $twig->addExtension(new CodeExtension(null, '', $container->get(TwigApplicationPlugin::SERVICE_CHARSET)));
            $twig->addExtension(new WebProfilerExtension());
            $twig->addExtension(new ProfilerExtension(new Profile(), $container->get(static::SERVICE_STOPWATCH)));

            return $twig;
        });

        $container->extend('twig.loader.filesystem', function (FilesystemLoaderInterface $loader) {
            $reflectionClass = new ReflectionClass(WebDebugToolbarListener::class);
            $templatePaths = dirname(dirname((string)$reflectionClass->getFileName())) . '/Resources/views';

            $loader->addPath($templatePaths, 'WebProfiler');

            return $loader;
        });

        $container->extend(RouterApplicationPlugin::SERVICE_CHAIN_ROUTER, function (ChainRouter $chainRouter, ContainerInterface $container) {
            $chainRouter->add($this->getRouter($container));

            return $chainRouter;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    protected function getRouter(ContainerInterface $container): RouterInterface
    {
        $loader = new ClosureLoader();

        $resource = function () use ($container) {
            $routeCollection = new RouteCollection();
            foreach ($this->getRouteDefinitions($container) as $routeDefinition) {
                [$pathinfo, $controllerKey, $routeName] = $routeDefinition;

                $route = new Route($pathinfo);
                $route->method('GET');

                $route->setDefault('_controller', $controllerKey);

                $routeCollection->add($routeName, $route);
            }

            return $routeCollection;
        };

        return new Router($loader, $resource, []);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return array
     */
    protected function getRouteDefinitions(ContainerInterface $container): array
    {
        $profilerController = function () use ($container) {
            return new ProfilerController($container->get(RouterApplicationPlugin::SERVICE_CHAIN_ROUTER), $container->get(static::SERVICE_PROFILER), $container->get(TwigApplicationPlugin::SERVICE_TWIG), $this->getDataCollectorPluginTemplates());
        };

        $routerController = function () use ($container) {
            return new RouterController($container->get(static::SERVICE_PROFILER), $container->get(TwigApplicationPlugin::SERVICE_TWIG), $container->get(RouterApplicationPlugin::SERVICE_CHAIN_ROUTER));
        };

        $exceptionController = function () use ($container) {
            return new ExceptionController($container->get(static::SERVICE_PROFILER), $container->get(TwigApplicationPlugin::SERVICE_TWIG), $container->get('debug'));
        };

        return [
            ['/_profiler/router/{token}', [$routerController, 'panelAction'], '_profiler_router'],
            ['/_profiler/exception/{token}.css', [$exceptionController, 'cssAction'], '_profiler_exception_css'],
            ['/_profiler/exception/{token}', [$exceptionController, 'showAction'], '_profiler_exception'],
            ['/_profiler/search', [$profilerController, 'searchAction'], '_profiler_search'],
            ['/_profiler/search_bar', [$profilerController, 'searchBarAction'], '_profiler_search_bar'],
            ['/_profiler/purge', [$profilerController, 'purgeAction'], '_profiler_purge'],
            ['/_profiler/info/{about}', [$profilerController, 'infoAction'], '_profiler_info'],
            ['/_profiler/phpinfo', [$profilerController, 'phpinfoAction'], '_profiler_phpinfo'],
            ['/_profiler/{token}/search/results', [$profilerController, 'searchResultsAction'], '_profiler_search_results'],
            ['/_profiler/{token}', [$profilerController, 'panelAction'], '_profiler'],
            ['/_profiler/wdt/{token}', [$profilerController, 'toolbarAction'], '_wdt'],
            ['/_profiler/', [$profilerController, 'homeAction'], '_profiler_home'],
        ];
    }

    /**
     * @return array
     */
    protected function getDataCollectorPluginTemplates(): array
    {
        $dataCollectorTemplates = [];
        foreach ($this->getFactory()->getDataCollectorPlugins() as $dataCollectorPlugin) {
            $dataCollectorTemplates[] = [
                $dataCollectorPlugin->getName(),
                $dataCollectorPlugin->getTemplateName(),
            ];
        }

        return $dataCollectorTemplates;
    }

    /**
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        $dispatcher = $container->get(EventDispatcherApplicationPlugin::SERVICE_DISPATCHER);

        $dispatcher->addSubscriber(new ProfilerListener($container->get(static::SERVICE_PROFILER), $container->get(static::SERVICE_REQUEST_STACK), null, false, false));
        $dispatcher->addSubscriber(new WebDebugToolbarListener($container->get(TwigApplicationPlugin::SERVICE_TWIG), false, WebDebugToolbarListener::ENABLED));
        $dispatcher->addSubscriber($container->get(static::SERVICE_PROFILER)->get(static::SERVICE_REQUEST));

        return $container;
    }
}
