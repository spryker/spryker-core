<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Client\Currency\Plugin\CurrencyPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;

class MoneyDependencyProvider extends AbstractDependencyProvider
{
    public const STORE = 'store';
    public const PLUGIN_CURRENCY = 'currency plugin';
    public const MONEY_PARSER = 'money parser';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addCurrencyPlugin($container);
        $container = $this->addMoneyParser($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return $this->getStore();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyPlugin(Container $container)
    {
        $container[static::PLUGIN_CURRENCY] = function () {
            return new CurrencyPlugin();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyParser(Container $container)
    {
        $container[static::MONEY_PARSER] = function () {
            $moneyToParserBridge = new MoneyToParserBridge($this->getIntlMoneyParser());

            return $moneyToParserBridge;
        };

        return $container;
    }

    /**
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function getIntlMoneyParser()
    {
        $numberFormatter = $this->getNumberFormatter();
        $currencies = $this->getIsoCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @return \NumberFormatter
     */
    protected function getNumberFormatter()
    {
        $numberFormatter = new NumberFormatter(
            $this->getStore()->getCurrentLocale(),
            NumberFormatter::CURRENCY
        );

        return $numberFormatter;
    }

    /**
     * @return \Money\Currencies\ISOCurrencies
     */
    protected function getIsoCurrencies()
    {
        $isoCurrencies = new ISOCurrencies();

        return $isoCurrencies;
    }
}
