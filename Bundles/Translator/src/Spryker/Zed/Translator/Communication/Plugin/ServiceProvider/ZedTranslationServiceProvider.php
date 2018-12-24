<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Translator\Business\Finder\TranslationFinder;
use Spryker\Zed\Translator\Business\Translator\Translator;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Twig_Environment;

/**
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
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
        $csvFileLoader = new CsvFileLoader();
        $csvFileLoader->setCsvControl($this->getConfig()->getDelimiter());
        $xlfFileLoader = new XliffFileLoader();
        $translator = new Translator(
            $app['locale'],
            null,
            $this->getConfig()->getCacheDir()
        );
        $translationFinder = new TranslationFinder($this->getConfig());
        $translator->setLazyLoadResources($translationFinder);
        $translator->addLoader($translationFinder->getFileFormat(), $csvFileLoader);
        $translator->setFallbackLocales($this->getConfig()->getFallbackLocales($app['locale']));
        $translator->addLoader('xlf', $xlfFileLoader);
        $locales = $this->getFactory()
            ->getStore()
            ->getLocales();
        foreach ($locales as $country => $locale) {
            $translator->addResource('xlf', $this->getConfig()->getValidatorsTranslationPath($country), $locale, 'validators');
        }
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) use ($translator) {
                $translator->addAsTwigExtension($twig);

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
