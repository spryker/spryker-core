<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyBridge;
use Spryker\Zed\Money\Dependency\Facade\MoneyToLocaleFacadeBridge;
use Spryker\Zed\Money\Dependency\Facade\MoneyToStoreBridge;

/**
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 */
class MoneyDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const MONEY_PARSER = 'money parser';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCurrencyFacade($container);
        $container = $this->addMoneyParser($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStoreFacade($container);

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
    protected function addCurrencyFacade(Container $container)
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new MoneyToCurrencyBridge($container->getLocator()->currency()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyParser(Container $container)
    {
        $container->set(static::MONEY_PARSER, function (Container $container) {
            $moneyToParserBridge = new MoneyToParserBridge($this->getIntlMoneyParser($container));

            return $moneyToParserBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function getIntlMoneyParser(Container $container)
    {
        $numberFormatter = $this->getNumberFormatter($container);
        $currencies = $this->getIsoCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \NumberFormatter
     */
    protected function getNumberFormatter(Container $container)
    {
        $numberFormatter = new NumberFormatter(
            $container->getLocator()->locale()->facade()->getCurrentLocale()->getLocaleNameOrFail(),
            NumberFormatter::CURRENCY,
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
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new MoneyToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new MoneyToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }
}
