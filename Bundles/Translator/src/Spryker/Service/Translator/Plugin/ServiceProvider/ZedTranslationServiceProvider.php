<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Service\Kernel\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig_Environment;

/**
 * @method \Spryker\Service\Translator\TranslatorServiceFactory getFactory()
 * @method \Spryker\Service\Translator\TranslatorConfig getConfig()
 */
class ZedTranslationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register(Application $app): void
    {
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function boot(Application $app): void
    {
        $translator = $this->getFactory()->createTranslator();

        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($translator) {
                $twig->addExtension(new TranslationExtension($translator));

                return $twig;
            })
        );

        $app['translator'] = $translator;
    }
}
