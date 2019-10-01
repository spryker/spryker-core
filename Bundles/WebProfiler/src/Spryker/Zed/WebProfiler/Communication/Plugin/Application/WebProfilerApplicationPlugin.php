<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WebProfiler\Communication\Plugin\Application;

use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\ProfilerExtension;
use Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Bundle\WebProfilerBundle\Controller\RouterController;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension;
use Symfony\Cmf\Component\Routing\ChainRouter;
use Symfony\Component\HttpKernel\Debug\FileLinkFormatter;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\EventListener\ProfilerListener;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

/**
 * @method \Spryker\Zed\WebProfiler\Communication\WebProfilerCommunicationFactory getFactory()
 * @method \Spryker\Zed\WebProfiler\WebProfilerConfig getConfig()
 */
class WebProfilerApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface, BootableApplicationPluginInterface
{
    public const SERVICE_STOPWATCH = 'stopwatch';
    public const SERVICE_LOGGER = 'logger';
    public const SERVICE_PROFILER = 'profiler';
    public const SERVICE_TWIG_PROFILE = 'profile';

    /**
     * @see \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @see \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_CHARSET
     */
    public const SERVICE_CHARSET = 'charset';

    /**
     * @see \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     */
    public const SERVICE_DISPATCHER = 'dispatcher';

    public const SERVICE_REQUEST = 'request';
    public const SERVICE_REQUEST_STACK = 'request_stack';
    public const SERVICE_ROUTER = 'routers';

    protected const ROUTER_PRIORITY = 10;

    /**
     * {@inheritDoc}
     * - Provides a WebProfiler which collects data from WebProfilerDataCollectorPluginInterface's and adds a toolbar at the bottom opf the page.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        if (!$this->getConfig()->isWebProfilerEnabled()) {
            return $container;
        }

        $container = $this->extendEventDispatcher($container);
        $container = $this->extendRouter($container);
        $container = $this->extendTwig($container);

        $container->set(static::SERVICE_STOPWATCH, function () {
            return $this->getFactory()->createStopwatch();
        });

        $container->set(static::SERVICE_PROFILER, function (Container $container) {
            $profiler = $this->getFactory()->createProfiler();
            $profiler = $this->addDataCollectorPlugins($profiler, $container);

            return $profiler;
        });

        $container->set(static::SERVICE_TWIG_PROFILE, function () {
            return $this->getFactory()->createProfile();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendEventDispatcher(ContainerInterface $container): ContainerInterface
    {
        $container->extend(static::SERVICE_DISPATCHER, function (EventDispatcher $dispatcher, ContainerInterface $container) {
            return new TraceableEventDispatcher($dispatcher, $container->get(static::SERVICE_STOPWATCH), $container->get(static::SERVICE_LOGGER));
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendRouter(ContainerInterface $container): ContainerInterface
    {
        $container->extend(static::SERVICE_ROUTER, function (ChainRouter $chainRouter, ContainerInterface $container) {
            $chainRouter->add($this->getRouter($container), static::ROUTER_PRIORITY);

            return $chainRouter;
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function extendTwig(ContainerInterface $container): ContainerInterface
    {
        $container->extend(static::SERVICE_TWIG, function (Environment $twig, ContainerInterface $container) {
            $fileLinkFormatter = new FileLinkFormatter(null);
            $twig->addExtension(new CodeExtension($fileLinkFormatter, '', $container->get(static::SERVICE_CHARSET)));
            $twig->addExtension(new WebProfilerExtension());
            $twig->addExtension(new ProfilerExtension($container->get(static::SERVICE_TWIG_PROFILE), $container->get(static::SERVICE_STOPWATCH)));

            return $twig;
        });

        return $container;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Profiler\Profiler $profiler
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Profiler\Profiler
     */
    protected function addDataCollectorPlugins(Profiler $profiler, ContainerInterface $container): Profiler
    {
        foreach ($this->getFactory()->getDataCollectorPlugins() as $dataCollectorPlugin) {
            $profiler->add($dataCollectorPlugin->getDataCollector($container));
        }

        return $profiler;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    protected function getRouter(ContainerInterface $container): RouterInterface
    {
        $loader = new ClosureLoader();

        $resource = function () use ($container) {
            $routeCollection = new RouteCollection();
            foreach ($this->getRouteDefinitions($container) as $routeDefinition) {
                [$pathinfo, $controllerKey, $routeName] = $routeDefinition;

                $route = new Route($pathinfo);
                $route->setMethods('GET');
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
            return new ProfilerController(
                $container->get(static::SERVICE_ROUTER),
                $container->get(static::SERVICE_PROFILER),
                $container->get(static::SERVICE_TWIG),
                $this->getDataCollectorPluginTemplates()
            );
        };

        $routerController = function () use ($container) {
            return new RouterController(
                $container->get(static::SERVICE_PROFILER),
                $container->get(static::SERVICE_TWIG),
                $container->get(static::SERVICE_ROUTER)
            );
        };

        $exceptionController = function () use ($container) {
            return new ExceptionController(
                $container->get(static::SERVICE_PROFILER),
                $container->get(static::SERVICE_TWIG),
                $container->get('debug')
            );
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
     * {@inheritDoc}
     * - Adds subscriber to the EventDispatcher when the WebProfiler is enabled.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface
    {
        if (!$this->getConfig()->isWebProfilerEnabled()) {
            return $container;
        }

        $dispatcher = $container->get(static::SERVICE_DISPATCHER);

        $dispatcher->addSubscriber(new ProfilerListener($container->get(static::SERVICE_PROFILER), $container->get(static::SERVICE_REQUEST_STACK), null, false, false));
        $dispatcher->addSubscriber(new WebDebugToolbarListener($container->get(static::SERVICE_TWIG), false, WebDebugToolbarListener::ENABLED));
        $dispatcher->addSubscriber($container->get(static::SERVICE_PROFILER)->get(static::SERVICE_REQUEST));

        return $container;
    }
}
