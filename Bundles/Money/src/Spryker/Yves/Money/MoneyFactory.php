<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapper;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Shared\Money\Parser\Parser;
use Spryker\Yves\Currency\Plugin\CurrencyPlugin;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Money\Mapper\MoneyToTransferMapper;

class MoneyFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Shared\Money\Builder\MoneyBuilderInterface
     */
    public function createMoneyBuilder()
    {
        return new MoneyBuilder(
            $this->createMoneyToTransferMapper(),
            $this->createDecimalToIntegerConverter(),
            $this->getStore()->getCurrencyIsoCode()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterWithTypeInterface
     */
    public function createMoneyFormatter()
    {
        return new MoneyFormatter(
            $this->createFormatterCollection()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface
     */
    protected function createFormatterCollection()
    {
        $moneyFormatterCollection = new MoneyFormatterCollection();
        $moneyFormatterCollection->addFormatter(
            $this->createIntlFormatterCurrency(),
            MoneyConstants::FORMATTER_WITH_SYMBOL
        );

        $moneyFormatterCollection->addFormatter(
            $this->createIntlFormatterDecimal(),
            MoneyConstants::FORMATTER_WITHOUT_SYMBOL
        );

        return $moneyFormatterCollection;
    }

    /**
     * @return \Spryker\Shared\Money\Parser\Parser
     */
    public function createMoneyParser()
    {
        return new Parser(
            $this->createIntlMoneyParser(),
            $this->createMoneyToTransferMapper()
        );
    }

    /**
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function createIntlMoneyParser()
    {
        $numberFormatter = $this->createNumberFormatter();
        $currencies = $this->createCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @return \NumberFormatter
     */
    protected function createNumberFormatter()
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
    protected function createCurrencies()
    {
        return new ISOCurrencies();
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface
     */
    protected function createMoneyToTransferMapper()
    {
        return new MoneyToTransferMapper(
            $this->getCurrencyPlugin()
        );
    }

    /**
     * @return \Spryker\Yves\Currency\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return new CurrencyPlugin();
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface
     */
    protected function createTransferToMoneyMapper()
    {
        return new TransferToMoneyMapper();
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    protected function createIntlFormatterCurrency()
    {
        return new IntlMoneyFormatterWithCurrency(
            $this->createTransferToMoneyMapper()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    protected function createIntlFormatterDecimal()
    {
        return new IntlMoneyFormatterWithoutCurrency(
            $this->createTransferToMoneyMapper()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    public function createIntegerToDecimalConverter()
    {
        return new IntegerToDecimalConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function createDecimalToIntegerConverter()
    {
        return new DecimalToIntegerConverter();
    }

}
