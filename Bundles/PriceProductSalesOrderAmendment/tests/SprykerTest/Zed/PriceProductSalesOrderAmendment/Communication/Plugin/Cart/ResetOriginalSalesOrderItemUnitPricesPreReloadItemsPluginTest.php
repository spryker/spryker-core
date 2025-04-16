<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart\ResetOriginalSalesOrderItemUnitPricesPreReloadItemsPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group ResetOriginalSalesOrderItemUnitPricesPreReloadItemsPluginTest
 * Add your own group annotations below this line
 */
class ResetOriginalSalesOrderItemUnitPricesPreReloadItemsPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testShouldResetOriginalSalesOrderItemUnitPrices(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setOriginalSalesOrderItemUnitPrices([
                'sku1' => 200,
            ]);

        // Act
        $quoteTransfer = (new ResetOriginalSalesOrderItemUnitPricesPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertEmpty($quoteTransfer->getOriginalSalesOrderItemUnitPrices());
    }
}
