<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyBridge;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class MoneyDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE = 'store';
    const FACADE_CURRENCY = 'currency facade';
    const INTL_MONEY_PARSER = 'intl money parser';
    const ISO_CURRENCIES = 'iso currencies';
    const NUMBER_FORMATTER = 'number formatter';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addIntlMoneyParser($container);
        $container = $this->addIsoCurrencies($container);
        $container = $this->addNumberFormatter($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new MoneyToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addIntlMoneyParser(Container $container)
    {
        $container[static::INTL_MONEY_PARSER] = function (Container $container) {
            $numberFormatter = $container[static::NUMBER_FORMATTER];
            $currencies = $container[static::ISO_CURRENCIES];
            $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

            return $intlMoneyParser;
        };


        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addIsoCurrencies(Container $container)
    {
        $container[self::ISO_CURRENCIES] = function () {
            return new ISOCurrencies();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addNumberFormatter(Container $container)
    {
        $container[self::NUMBER_FORMATTER] = function (Container $container) {
            $store = $container[static::STORE];
            $numberFormatter = new NumberFormatter(
                $store->getCurrentLocale(),
                NumberFormatter::CURRENCY
            );

            return $numberFormatter;
        };

        return $container;
    }

}
