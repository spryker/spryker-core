<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyBridge;
use Spryker\Zed\Money\Dependency\Facade\MoneyToStoreBridge;

class MoneyDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE = 'store';

    public const FACADE_CURRENCY = 'currency facade';
    public const FACADE_STORE = 'store facade';

    public const MONEY_PARSER = 'money parser';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyParser($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);

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
            return $this->getStore();
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new MoneyToStoreBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }
}
