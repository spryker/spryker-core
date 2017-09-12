<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * @method \Spryker\Yves\Currency\CurrencyFactory getFactory()
 */
class CurrencySwitcherServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    protected static $functionName = 'spyCurrencySwitch';

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Twig_Environment $twig) {
                $twig->addFunction(
                    static::$functionName,
                    $this->getCurrencySwitcher($twig)
                );

                return $twig;
            })
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     * s
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_SimpleFunction
     */
    protected function getCurrencySwitcher(Twig_Environment $twig)
    {
        $options = ['is_safe' => ['html']];

        return new Twig_SimpleFunction(static::$functionName, function () use ($twig) {
            return $twig->render(
                '@Currency/partial/currency_switcher.twig',
                [
                    'currencies' => $this->getCurrencies(),
                    'currentCurrency' => $this->getCurrentCurrency(),
                ]
            );
        }, $options);
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected function getCurrencies()
    {
        $currencyBuilder = $this->getFactory()->createCurrencyBuilder();
        $availableCurrencyCodes = $this->getFactory()->getStore()->getCurrencyIsoCodes();

        $currencies = [];
        foreach ($availableCurrencyCodes as $currency) {
            $currencies[$currency] = $currencyBuilder->fromIsoCode($currency);
        }

        return $currencies;
    }

    /**
     * @return string
     */
    protected function getCurrentCurrency()
    {
        $currentCurrencyIsoCode = $this->getFactory()->createCurrencyPersistence()->getCurrentCurrencyIsoCode();

        if (!$currentCurrencyIsoCode) {
            return $this->getFactory()->getStore()->getCurrencyIsoCode();
        }
        return $currentCurrencyIsoCode;
    }

}
