<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig_Environment;

/**
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class ZedTranslationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $translator = $this->getFactory()->getTranslatorService()->getTranslator();

        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($translator) {
                $twig->addExtension(new TranslationExtension($translator));

                return $twig;
            })
        );

        $app['translator'] = $translator;
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }
}
