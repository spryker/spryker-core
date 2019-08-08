<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\ContainerGlobals;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Form\FormRenderer;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

class FormFactoryServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $containerGlobals = new ContainerGlobals();
        $containerGlobals[ApplicationConstants::FORM_FACTORY] = $containerGlobals->share(function () use ($app) {
            return $app['form.factory'];
        });

        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($app) {
                $data = [
                    FormRenderer::class => function () use ($app) {
                        return $app['twig.form.renderer'];
                    },
                ];
                if (class_exists(TwigRenderer::class)) {
                    $data[TwigRenderer::class] = function () use ($app) {
                        return $app['twig.form.renderer'];
                    };
                }

                $twig->addRuntimeLoader(new FactoryRuntimeLoader($data));

                return $twig;
            })
        );
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
