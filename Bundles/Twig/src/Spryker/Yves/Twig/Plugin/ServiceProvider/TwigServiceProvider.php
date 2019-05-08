<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

/**
 * @deprecated Use \Spryker\Yves\Twig\Plugin\Application\TwigApplicationPlugin instead.
 *
 * @method \Spryker\Yves\Twig\TwigFactory getFactory()
 */
class TwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const TWIG_LOADER_YVES = 'twig.loader.yves';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[static::TWIG_LOADER_YVES] = function () {
            return $this->getFactory()->createFilesystemLoader();
        };

        if (class_exists('\Symfony\Bridge\Twig\Extension\HttpKernelRuntime')) {
            $app['twig'] = $app->share(
                $app->extend(
                    'twig',
                    function (Environment $twig) use ($app) {
                        $callback = function () use ($app) {
                            $fragmentHandler = new FragmentHandler($app['request_stack'], $app['fragment.renderers']);

                            return new HttpKernelRuntime($fragmentHandler);
                        };
                        $factoryLoader = new FactoryRuntimeLoader([HttpKernelRuntime::class => $callback]);
                        $twig->addRuntimeLoader($factoryLoader);

                        return $twig;
                    }
                )
            );
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
