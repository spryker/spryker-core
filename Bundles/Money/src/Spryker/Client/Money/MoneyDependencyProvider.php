<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Money\Dependency\Client\MoneyToCurrencyClientBridge;
use Spryker\Client\Money\Dependency\Client\MoneyToLocaleClientBridge;
use Spryker\Client\Money\Dependency\Client\MoneyToLocaleClientInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;

class MoneyDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const MONEY_PARSER = 'money parser';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addCurrencyClient($container);
        $container = $this->addMoneyParser($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CLIENT_CURRENCY, function ($container) {
            return new MoneyToCurrencyClientBridge(
                $container->getLocator()->currency()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function ($container) {
            return $this->getLocaleClient($container);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
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
        $container->set(static::MONEY_PARSER, function (Container $container) {
            $moneyToParserBridge = new MoneyToParserBridge($this->getIntlMoneyParser($container));

            return $moneyToParserBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
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
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \NumberFormatter
     */
    protected function getNumberFormatter(Container $container)
    {
        $numberFormatter = new NumberFormatter(
            $this->getLocaleClient($container)->getCurrentLocale(),
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
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Money\Dependency\Client\MoneyToLocaleClientInterface
     */
    protected function getLocaleClient(Container $container): MoneyToLocaleClientInterface
    {
        return new MoneyToLocaleClientBridge(
            $container->getLocator()->locale()->client(),
        );
    }
}
