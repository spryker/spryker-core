<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Converter\DecimalToCentConverter;
use Spryker\Shared\Money\Converter\CentToDecimalConverter;
use Spryker\Shared\Money\Converter\MoneyToTransferConverter;
use Spryker\Shared\Money\Converter\TransferToMoneyConverter;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\Money\MoneyConstants;
use Spryker\Yves\Kernel\AbstractFactory;

class MoneyFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Shared\Money\Builder\MoneyBuilderInterface
     */
    public function createMoneyBuilder()
    {
        return new MoneyBuilder(
            $this->createMoneyToTransferConverter(),
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
        return Store::getInstance();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\MoneyToTransferConverterInterface
     */
    protected function createMoneyToTransferConverter()
    {
        return new MoneyToTransferConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\TransferToMoneyConverterInterface
     */
    protected function createTransferToMoneyConverter()
    {
        return new TransferToMoneyConverter();
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
    public function createIntegerToFloatConverter()
    {
        return new CentToDecimalConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToCentConverterInterface
     */
    public function createFloatToIntegerConverter()
    {
        return new DecimalToCentConverter();
    }

}
