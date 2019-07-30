<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin\ServiceProvider;

use FilesystemIterator;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Twig\Communication\RouteResolver\RouteResolver;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

/**
 * @deprecated Use \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin instead.
 *
 * @method \Spryker\Zed\Twig\TwigConfig getConfig()
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
class TwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var \Silex\Application|\Spryker\Shared\Kernel\Communication\Application
     */
    private $app;

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->app = $app;

        $this->provideFormTypeTemplates();

        $app['twig.loader.zed'] = $app->share(function () {
            return $this->getFactory()->createFilesystemLoader();
        });

        $app['twig.loader'] = $app->share(function ($app) {
            return new ChainLoader(
                [
                    $app['twig.loader.zed'],
                    $app['twig.loader.filesystem'],
                ]
            );
        });

        $app['twig.options'] = Config::get(TwigConstants::ZED_TWIG_OPTIONS);

        $app['twig.global.variables'] = $app->share(function () {
            return [];
        });

        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function (Environment $twig) use ($app) {
                    foreach ($app['twig.global.variables'] as $name => $value) {
                        $twig->addGlobal($name, $value);
                    }

                    if (class_exists('\Symfony\Bridge\Twig\Extension\HttpKernelRuntime')) {
                        $callback = function () use ($app) {
                            $fragmentHandler = new FragmentHandler($app['request_stack'], $app['fragment.renderers']);

                            return new HttpKernelRuntime($fragmentHandler);
                        };
                        $factoryLoader = new FactoryRuntimeLoader([HttpKernelRuntime::class => $callback]);
                        $twig->addRuntimeLoader($factoryLoader);
                    }

                    return $twig;
                }
            )
        );
    }

    /**
     * Handles string responses.
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent $event The event to handle
     *
     * @return void
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();

        if (empty($response) || is_array($response)) {
            $response = $this->render((array)$response);
            if ($response instanceof Response) {
                $event->setResponse($response);
            }
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::VIEW, [$this, 'onKernelView']);
    }

    /**
     * Renders the template for the current controller/action
     *
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    protected function render(array $parameters = [])
    {
        $request = $this->app['request_stack']->getCurrentRequest();
        $controller = $request->attributes->get('_controller');

        if ($request->attributes->has('_template')) {
            return $this->renderTemplateFromRouterCache($request, $parameters);
        }

        if (!is_string($controller) || empty($controller)) {
            return null;
        }

        if (isset($parameters['alternativeRoute'])) {
            $route = (string)$parameters['alternativeRoute'];
        } else {
            $route = (new RouteResolver())
                ->buildRouteFromControllerServiceName($controller);
        }

        return $this->app->render('@' . $route . '.twig', $parameters);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderTemplateFromRouterCache(Request $request, array $parameters): Response
    {
        return $this->app->render('@' . $request->attributes->get('_template') . '.twig', $parameters);
    }

    /**
     * @return void
     */
    protected function provideFormTypeTemplates()
    {
        $guiDirectory = $path = $this->getConfig()->getBundlesDirectory() . '/Gui';
        if (!is_dir($guiDirectory)) {
            $guiDirectory = $path = $this->getConfig()->getBundlesDirectory() . '/gui';
        }
        $path = $guiDirectory . '/src/Spryker/Zed/Gui/Presentation/Form/Type';

        $this->app->extend('twig.loader.filesystem', function (FilesystemLoader $loader) use ($path) {
            $loader->addPath($path);

            return $loader;
        });

        $files = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::KEY_AS_PATHNAME);

        $typeTemplates = [];
        foreach ($files as $file) {
            $typeTemplates[] = $file->getFilename();
        }

        $this->app['twig.form.templates'] = array_merge([
            'bootstrap_3_layout.html.twig',
        ], $typeTemplates);
    }
}
