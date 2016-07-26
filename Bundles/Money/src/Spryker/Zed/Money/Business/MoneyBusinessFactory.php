<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Business;

use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Converter\CentToDecimalConverter;
use Spryker\Shared\Money\Converter\DecimalToCentConverter;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapper;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapper;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Money\MoneyDependencyProvider;

class MoneyBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\Money\Builder\MoneyBuilderInterface
     */
    public function createMoneyBuilder()
    {
        return new MoneyBuilder(
            $this->createMoneyToTransferConverter(),
            $this->createDecimalToCentConverter(),
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
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface
     */
    protected function createMoneyToTransferConverter()
    {
        return new MoneyToTransferMapper();
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface
     */
    protected function createTransferToMoneyConverter()
    {
        return new TransferToMoneyMapper();
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    protected function createIntlFormatterCurrency()
    {
        return new IntlMoneyFormatterWithCurrency(
            $this->createTransferToMoneyConverter()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    protected function createIntlFormatterDecimal()
    {
        return new IntlMoneyFormatterWithoutCurrency(
            $this->createTransferToMoneyConverter()
        );
    }

    /**
     * @return \Spryker\Shared\Money\Converter\CentToDecimalConverterInterface
     */
    public function createCentToDecimalConverter()
    {
        return new CentToDecimalConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToCentConverterInterface
     */
    public function createDecimalToCentConverter()
    {
        return new DecimalToCentConverter();
    }

}
