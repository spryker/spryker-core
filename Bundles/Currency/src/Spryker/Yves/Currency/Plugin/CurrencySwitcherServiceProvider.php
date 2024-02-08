<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Plugin;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Yves\Currency\CurrencyFactory getFactory()
 * @method \Spryker\Client\Currency\CurrencyClientInterface getClient()
 */
class CurrencySwitcherServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var string
     */
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
            $app->extend('twig', function (Environment $twig) {
                $twig->addFunction(
                    $this->getCurrencySwitcher($twig),
                );

                return $twig;
            }),
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
    public function boot(Application $app): void
    {
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getCurrencySwitcher(Environment $twig): TwigFunction
    {
        $options = ['is_safe' => ['html']];

        return new TwigFunction(static::$functionName, function () use ($twig) {
            return $twig->render(
                '@Currency/partial/currency_switcher.twig',
                [
                    'currencies' => $this->getCurrencies(),
                    'currentCurrency' => $this->getCurrentCurrency(),
                ],
            );
        }, $options);
    }

    /**
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected function getCurrencies(): array
    {
        $availableCurrencyCodes = $this->getClient()->getCurrencyIsoCodes();

        $currencies = [];
        foreach ($availableCurrencyCodes as $currencyIsoCode) {
            $currencies[$currencyIsoCode] = $this->getClient()->fromIsoCode($currencyIsoCode);
        }

        return $currencies;
    }

    /**
     * @return string
     */
    protected function getCurrentCurrency(): string
    {
        $currentCurrencyIsoCode = $this->getClient()->getCurrent()->getCode();

        if (!$currentCurrencyIsoCode) {
            return $this->getCurrencyIsoCode();
        }

        return $currentCurrencyIsoCode;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return string
     */
    protected function getCurrencyIsoCode(): string
    {
        return (string)$this->getClient()->getCurrent()->getCode();
    }
}
