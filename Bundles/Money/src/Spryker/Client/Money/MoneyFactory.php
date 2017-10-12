<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Money\Mapper\MoneyToTransferMapper;
use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapper;
use Spryker\Shared\Money\Parser\Parser;

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
            MoneyFormatterCollection::FORMATTER_WITH_SYMBOL
        );

        $moneyFormatterCollection->addFormatter(
            $this->createIntlFormatterDecimal(),
            MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL
        );

        return $moneyFormatterCollection;
    }

    /**
     * @return \Spryker\Shared\Money\Parser\ParserInterface
     */
    public function createMoneyParser()
    {
        return new Parser(
            $this->createIntlMoneyParser(),
            $this->createMoneyToTransferMapper()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Parser\MoneyToParserInterface
     */
    protected function createIntlMoneyParser()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::MONEY_PARSER);
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
     * @return \Spryker\Client\Currency\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::PLUGIN_CURRENCY);
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
