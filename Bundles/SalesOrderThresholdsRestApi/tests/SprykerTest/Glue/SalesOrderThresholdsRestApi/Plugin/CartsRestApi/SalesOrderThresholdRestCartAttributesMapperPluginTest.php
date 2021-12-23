<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\SalesOrderThresholdsRestApi\Plugin\CartsRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdValueTransfer;
use Spryker\Glue\SalesOrderThresholdsRestApi\Plugin\CartsRestApi\SalesOrderThresholdRestCartAttributesMapperPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group SalesOrderThresholdsRestApi
 * @group Plugin
 * @group CartsRestApi
 * @group SalesOrderThresholdRestCartAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class SalesOrderThresholdRestCartAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testQuoteTransferToRestCartsAttributesMap(): void
    {
        $salesOrderThresholdTypeTransfer = (new SalesOrderThresholdTypeTransfer())
            ->setKey('hard-minimum-threshold');

        $salesOrderThresholdValueTransfer = (new SalesOrderThresholdValueTransfer())
            ->setThreshold(100000)
            ->setDeltaWithSubtotal(86184)
            ->setMessage('You need to add items for €1,000.00 to pass a recommended threshold, you cannot proceed to checkout.')
            ->setSalesOrderThresholdType($salesOrderThresholdTypeTransfer);

        $quoteTransfer = (new QuoteTransfer())->addSalesOrderThresholdValue($salesOrderThresholdValueTransfer);

        $restCartsAttributesTransfer = (new RestCartsAttributesTransfer());
        $salesOrderThresholdRestCartAttributesMapperPlugin = new SalesOrderThresholdRestCartAttributesMapperPlugin();

        // Act
        $restCartsAttributesTransfer = $salesOrderThresholdRestCartAttributesMapperPlugin->mapQuoteTransferToRestCartAttributesTransfer(
            $quoteTransfer,
            $restCartsAttributesTransfer,
        );

        // Assert
        $restCartsThresholdsTransfers = $restCartsAttributesTransfer->getThresholds();
        $restCartsThresholdsTransfer = $restCartsThresholdsTransfers[0];

        $this->assertCount(1, $restCartsThresholdsTransfers);
        $this->assertSame(
            $restCartsThresholdsTransfer->getType(),
            $salesOrderThresholdValueTransfer->getSalesOrderThresholdType()->getKey(),
        );
        $this->assertSame($restCartsThresholdsTransfer->getThreshold(), $salesOrderThresholdValueTransfer->getThreshold());
        $this->assertSame($restCartsThresholdsTransfer->getDeltaWithSubtotal(), $salesOrderThresholdValueTransfer->getDeltaWithSubtotal());
        $this->assertSame($restCartsThresholdsTransfer->getFee(), $salesOrderThresholdValueTransfer->getFee());
        $this->assertSame($restCartsThresholdsTransfer->getMessage(), $salesOrderThresholdValueTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testMapQuoteTransferToRestCartAttributesTransferWithoutSalesOrderThresholdType(): void
    {
        $salesOrderThresholdValueTransfer = (new SalesOrderThresholdValueTransfer())
            ->setThreshold(100000)
            ->setDeltaWithSubtotal(86184)
            ->setMessage('You need to add items for €1,000.00 to pass a recommended threshold, but if you want can proceed to checkout.')
            ->setSalesOrderThresholdType(null);

        $quoteTransfer = (new QuoteTransfer())->addSalesOrderThresholdValue($salesOrderThresholdValueTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        (new SalesOrderThresholdRestCartAttributesMapperPlugin())
            ->mapQuoteTransferToRestCartAttributesTransfer($quoteTransfer, new RestCartsAttributesTransfer());
    }
}
