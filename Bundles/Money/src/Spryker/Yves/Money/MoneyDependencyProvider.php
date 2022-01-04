<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Dependency\Client\MoneyToLocaleClientBridge;
use Spryker\Yves\Money\Dependency\Client\MoneyToLocaleClientInterface;

class MoneyDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const MONEY_PARSER = 'money parser';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMoneyParser($container);
        $container = $this->addLocaleClient($container);

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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyParser(Container $container)
    {
        $container->set(static::MONEY_PARSER, function (Container $container) {
            $moneyToParserBridge = new MoneyToParserBridge(
                $this->getIntlMoneyParser(
                    $container->getLocator()->locale()->client()->getCurrentLocale(),
                ),
            );

            return $moneyToParserBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container): MoneyToLocaleClientInterface {
            return new MoneyToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param string $locale
     *
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function getIntlMoneyParser(string $locale)
    {
        $numberFormatter = $this->getNumberFormatter($locale);
        $currencies = $this->getIsoCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @param string $locale
     *
     * @return \NumberFormatter
     */
    protected function getNumberFormatter(string $locale): NumberFormatter
    {
        $numberFormatter = new NumberFormatter(
            $locale,
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
}
