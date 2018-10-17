<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfiler\Plugin\ServiceProvider;

use LogicException;
use ReflectionClass;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceControllerResolver;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Kernel\Store;
use Symfony\Bridge\Twig\DataCollector\TwigDataCollector;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\ProfilerExtension;
use Symfony\Bundle\WebProfilerBundle\Controller\ExceptionController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Bundle\WebProfilerBundle\Controller\RouterController;
use Symfony\Bundle\WebProfilerBundle\EventListener\WebDebugToolbarListener;
use Symfony\Bundle\WebProfilerBundle\Twig\WebProfilerExtension;
use Symfony\Component\Form\Extension\DataCollector\FormDataCollector;
use Symfony\Component\Form\Extension\DataCollector\FormDataExtractor;
use Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeFactoryDataCollectorProxy;
use Symfony\Component\Form\Extension\DataCollector\Type\DataCollectorTypeExtension;
use Symfony\Component\HttpKernel\DataCollector\ConfigDataCollector;
use Symfony\Component\HttpKernel\DataCollector\EventDataCollector;
use Symfony\Component\HttpKernel\DataCollector\ExceptionDataCollector;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;
use Symfony\Component\HttpKernel\DataCollector\MemoryDataCollector;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Symfony\Component\HttpKernel\DataCollector\RouterDataCollector;
use Symfony\Component\HttpKernel\DataCollector\TimeDataCollector;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\EventListener\ProfilerListener;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Stopwatch\Stopwatch;
use Twig\Profiler\Profile;

class WebProfilerServiceProvider implements ServiceProviderInterface, ControllerProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['profiler.cache_dir'] = function () {
            return APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/cache/profiler';
        };

        $app['profiler.mount_prefix'] = '/_profiler';

        $app['dispatcher'] = $app->share($app->extend('dispatcher', function ($dispatcher, $app) {
            return new TraceableEventDispatcher($dispatcher, $app['stopwatch'], $app['logger']);
        }));

        $app['data_collector.templates'] = $app->share(function () {
            $templates = [
                ['config',    '@WebProfiler/Collector/config.html.twig'],
                ['request',   '@WebProfiler/Collector/request.html.twig'],
                ['exception', '@WebProfiler/Collector/exception.html.twig'],
                ['events',    '@WebProfiler/Collector/events.html.twig'],
                ['logger',    '@WebProfiler/Collector/logger.html.twig'],
                ['time',      '@WebProfiler/Collector/time.html.twig'],
                ['router',    '@WebProfiler/Collector/router.html.twig'],
                ['memory',    '@WebProfiler/Collector/memory.html.twig'],
                ['form',      '@WebProfiler/Collector/form.html.twig'],
            ];
            if (class_exists(ProfilerExtension::class)) {
                $templates[] = ['twig', '@WebProfiler/Collector/twig.html.twig'];
            }

            return $templates;
        });

        $app['data_collectors'] = $app->share(function ($app) {
            return [
                'config' => $app->share(function ($app) {
                    return new ConfigDataCollector();
                }),
                'request' => $app->share(function ($app) {
                    return new RequestDataCollector();
                }),
                'exception' => $app->share(function ($app) {
                    return new ExceptionDataCollector();
                }),
                'events' => $app->share(function ($app) {
                    return new EventDataCollector($app['dispatcher']);
                }),
                'logger' => $app->share(function ($app) {
                    return new LoggerDataCollector($app['logger']);
                }),
                'time' => $app->share(function ($app) {
                    return new TimeDataCollector(null, $app['stopwatch']);
                }),
                'router' => $app->share(function ($app) {
                    return new RouterDataCollector();
                }),
                'memory' => $app->share(function ($app) {
                    return new MemoryDataCollector();
                }),
            ];
        });

        if (isset($app['form.resolved_type_factory']) && class_exists(FormDataCollector::class)) {
            $app['data_collectors.form.extractor'] = $app->share(function () {
                return new FormDataExtractor();
            });

            $app['data_collectors'] = $app->share($app->extend('data_collectors', function ($collectors, $app) {
                $collectors['form'] = $app->share(function ($app) {
                    return new FormDataCollector($app['data_collectors.form.extractor']);
                });

                return $collectors;
            }));

            $app['form.resolved_type_factory'] = $app->share($app->extend('form.resolved_type_factory', function ($factory, $app) {
                return new ResolvedTypeFactoryDataCollectorProxy($factory, $app['data_collectors']['form']($app));
            }));

            $app['form.type.extensions'] = $app->share($app->extend('form.type.extensions', function ($extensions, $app) {
                $extensions[] = new DataCollectorTypeExtension($app['data_collectors']['form']($app));

                return $extensions;
            }));
        }

        if (class_exists(ProfilerExtension::class)) {
            $app['data_collectors'] = $app->share($app->extend('data_collectors', function ($collectors, $app) {
                $collectors['twig'] = $app->share(function ($app) {
                    return new TwigDataCollector($app['twig.profiler.profile']);
                });

                return $collectors;
            }));

            $app['twig.profiler.profile'] = $app->share(function () {
                return new Profile();
            });
        }

        $app['web_profiler.controller.profiler'] = $app->share(function ($app) {
            return new ProfilerController($app['url_generator'], $app['profiler'], $app['twig'], $app['data_collector.templates'], $app['web_profiler.debug_toolbar.position']);
        });

        $app['web_profiler.controller.router'] = $app->share(function ($app) {
            return new RouterController($app['profiler'], $app['twig'], isset($app['url_matcher']) ? $app['url_matcher'] : null, $app['routes']);
        });

        $app['web_profiler.controller.exception'] = $app->share(function ($app) {
            return new ExceptionController($app['profiler'], $app['twig'], $app['debug']);
        });

        $app['web_profiler.toolbar.listener'] = $app->share(function ($app) {
            return new WebDebugToolbarListener($app['twig'], $app['web_profiler.debug_toolbar.intercept_redirects'], $app['web_profiler.debug_toolbar.position'], $app['url_generator']);
        });

        $app['profiler'] = $app->share(function ($app) {
            $profiler = new Profiler($app['profiler.storage'], $app['logger']);

            foreach ($app['data_collectors'] as $collector) {
                $profiler->add($collector($app));
            }

            return $profiler;
        });

        $app['profiler.storage'] = $app->share(function ($app) {
            return new FileProfilerStorage('file:' . $app['profiler.cache_dir']);
        });

        $app['profiler.request_matcher'] = null;
        $app['profiler.only_exceptions'] = false;
        $app['profiler.only_master_requests'] = false;
        $app['web_profiler.debug_toolbar.enable'] = true;
        $app['web_profiler.debug_toolbar.position'] = 'bottom';
        $app['web_profiler.debug_toolbar.intercept_redirects'] = false;

        $app['profiler.listener'] = $app->share(function ($app) {
            if (Kernel::VERSION_ID >= 20800) {
                return new ProfilerListener($app['profiler'], $app['request_stack'], $app['profiler.request_matcher'], $app['profiler.only_exceptions'], $app['profiler.only_master_requests']);
            } else {
                return new ProfilerListener($app['profiler'], $app['profiler.request_matcher'], $app['profiler.only_exceptions'], $app['profiler.only_master_requests'], $app['request_stack']);
            }
        });

        $app['stopwatch'] = $app->share(function () {
            return new Stopwatch();
        });

        $app['code.file_link_format'] = null;

        $app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
            $twig->addExtension(new CodeExtension($app['code.file_link_format'], '', $app['charset']));

            if (class_exists(WebProfilerExtension::class)) {
                $twig->addExtension(new WebProfilerExtension());
            }

            if (class_exists(ProfilerExtension::class)) {
                $twig->addExtension(new ProfilerExtension($app['twig.profiler.profile'], $app['stopwatch']));
            }

            return $twig;
        }));

        $app['twig.loader.filesystem'] = $app->share($app->extend('twig.loader.filesystem', function ($loader, $app) {
            $loader->addPath($app['profiler.templates_path'], 'WebProfiler');

            return $loader;
        }));

        $app['profiler.templates_path'] = function () {
            $reflectionClass = new ReflectionClass(WebDebugToolbarListener::class);

            return dirname(dirname((string)$reflectionClass->getFileName())) . '/Resources/views';
        };
    }

    /**
     * @param \Silex\Application $app
     *
     * @throws \LogicException
     *
     * @return mixed|\Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        if (!$app['resolver'] instanceof ServiceControllerResolver) {
            // using RuntimeException crashes PHP?!
            throw new LogicException('You must enable the ServiceController service provider to be able to use the WebProfiler.');
        }

        $controllers = $app['controllers_factory'];

        $controllers->get('/router/{token}', 'web_profiler.controller.router:panelAction')->bind('_profiler_router');
        $controllers->get('/exception/{token}.css', 'web_profiler.controller.exception:cssAction')->bind('_profiler_exception_css');
        $controllers->get('/exception/{token}', 'web_profiler.controller.exception:showAction')->bind('_profiler_exception');
        $controllers->get('/search', 'web_profiler.controller.profiler:searchAction')->bind('_profiler_search');
        $controllers->get('/search_bar', 'web_profiler.controller.profiler:searchBarAction')->bind('_profiler_search_bar');
        $controllers->get('/purge', 'web_profiler.controller.profiler:purgeAction')->bind('_profiler_purge');
        $controllers->get('/info/{about}', 'web_profiler.controller.profiler:infoAction')->bind('_profiler_info');
        $controllers->get('/phpinfo', 'web_profiler.controller.profiler:phpinfoAction')->bind('_profiler_phpinfo');
        $controllers->get('/{token}/search/results', 'web_profiler.controller.profiler:searchResultsAction')->bind('_profiler_search_results');
        $controllers->get('/{token}', 'web_profiler.controller.profiler:panelAction')->bind('_profiler');
        $controllers->get('/wdt/{token}', 'web_profiler.controller.profiler:toolbarAction')->bind('_wdt');
        $controllers->get('/', 'web_profiler.controller.profiler:homeAction')->bind('_profiler_home');

        return $controllers;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $dispatcher = $app['dispatcher'];

        $dispatcher->addSubscriber($app['profiler.listener']);

        if ($app['web_profiler.debug_toolbar.enable']) {
            $dispatcher->addSubscriber($app['web_profiler.toolbar.listener']);
        }

        $dispatcher->addSubscriber($app['profiler']->get('request'));
        $app->mount($app['profiler.mount_prefix'], $this->connect($app));
    }
}
