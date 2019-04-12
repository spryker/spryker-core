<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\Currency\Communication\Plugin\Twig\CurrencyTwigPlugin` instead.
 *
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 */
class TwigCurrencyServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const NO_SYMBOL_FOUND = '-';
    public const CURRENCY_SYMBOL_FUNCTION_NAME = 'currencySymbol';

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
            $app->extend('twig', function (Environment $twig) {
                $twig->addFunction($this->getCurrencySymbolFunction());

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
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getCurrencySymbolFunction()
    {
        return new TwigFunction(static::CURRENCY_SYMBOL_FUNCTION_NAME, function ($isoCode = null) {
            return $this->getCurrencySymbol($isoCode);
        });
    }

    /**
     * @param string|null $isoCode
     *
     * @return string
     */
    protected function getCurrencySymbol($isoCode = null)
    {
        $currencySymbol = static::NO_SYMBOL_FOUND;
        $currencyTransfer = $this->getCurrencyTransfer($isoCode);
        if ($currencyTransfer && $currencyTransfer->getSymbol() !== null) {
            $currencySymbol = $currencyTransfer->getSymbol();
        }

        return $currencySymbol;
    }

    /**
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer($isoCode = null)
    {
        if ($isoCode !== null) {
            return $this->getFacade()->fromIsoCode($isoCode);
        }

        return $this->getFacade()->getCurrent();
    }
}
