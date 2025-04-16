<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Quote\ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Quote
 * @group ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePluginTest
 * Add your own group annotations below this line
 */
class ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePluginTest extends Unit
{
    /**
     * @var array<string, int>
     */
    protected const TEST_ORIGINAL_SALES_ORDER_ITEM_UNIT_PRICES = [
        'test-sku' => 123,
    ];

    /**
     * @return void
     */
    public function testDoesNothingWhenQuoteHasItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addItem(new ItemTransfer())
            ->setOriginalSalesOrderItemUnitPrices(static::TEST_ORIGINAL_SALES_ORDER_ITEM_UNIT_PRICES);

        // Act
        $quoteTransfer = (new ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame(static::TEST_ORIGINAL_SALES_ORDER_ITEM_UNIT_PRICES, $quoteTransfer->getOriginalSalesOrderItemUnitPrices());
    }

    /**
     * @return void
     */
    public function testResetsQuoteOriginalSalesOrderItemUnitPrices(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setOriginalSalesOrderItemUnitPrices(static::TEST_ORIGINAL_SALES_ORDER_ITEM_UNIT_PRICES);

        // Act
        $quoteTransfer = (new ResetOriginalSalesOrderItemUnitPricesBeforeQuoteSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame([], $quoteTransfer->getOriginalSalesOrderItemUnitPrices());
    }
}
