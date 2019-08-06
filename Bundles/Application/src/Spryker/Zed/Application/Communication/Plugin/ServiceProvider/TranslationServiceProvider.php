<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Translator;
use Twig\Environment;

/**
 * @deprecated Use `Spryker\Zed\Translator\Communication\Plugin\Twig\TranslatorTwigPlugin` instead.
 *
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class TranslationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * Added for BC reason only.
     */
    protected const BC_FEATURE_FLAG_TWIG_TRANSLATOR = 'BC_FEATURE_FLAG_TWIG_TRANSLATOR';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[static::BC_FEATURE_FLAG_TWIG_TRANSLATOR] = true;
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($app) {
                if (!$app[static::BC_FEATURE_FLAG_TWIG_TRANSLATOR]) {
                    return $twig;
                }

                $translator = new Translator($app['locale']);
                $app['translator'] = $translator;
                $twig->addExtension(new TranslationExtension($translator));

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
