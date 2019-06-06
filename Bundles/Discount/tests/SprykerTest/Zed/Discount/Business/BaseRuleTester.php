<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface;

class BaseRuleTester extends Unit
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected function createComparatorMock()
    {
        return $this->getMockBuilder(ComparatorOperatorsInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected function createCurrencyConverterMock()
    {
        return $this->getMockBuilder(MoneyValueConverterInterface::class)->getMock();
    }

    /**
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransfer($value)
    {
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setValue($value);

        return $clauseTransfer;
    }

    /**
     * @param array $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(array $items = [])
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setPriceMode('GROSS_MODE');

        $currencyTransfer = new CurrencyTransfer();
        $currencyTransfer->setCode('EUR');
        $quoteTransfer->setCurrency($currencyTransfer);
        $quoteTransfer->setItems(new ArrayObject($items));

        return $quoteTransfer;
    }

    /**
     * @param int $price
     * @param int $quantity
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer($price = 0, $quantity = 0, $sku = '')
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitPrice($price);
        $itemTransfer->setQuantity($quantity);
        $itemTransfer->setSku($sku);

        return $itemTransfer;
    }
}
