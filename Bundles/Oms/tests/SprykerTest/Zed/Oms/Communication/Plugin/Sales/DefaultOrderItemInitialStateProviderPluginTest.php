<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Communication\Plugin\Sales;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Oms\Communication\Plugin\Sales\DefaultOrderItemInitialStateProviderPlugin;
use SprykerTest\Zed\Oms\OmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group DefaultOrderItemInitialStateProviderPluginTest
 * Add your own group annotations below this line
 */
class DefaultOrderItemInitialStateProviderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const STATE_NEW = 'new';

    /**
     * @var \SprykerTest\Zed\Oms\OmsCommunicationTester
     */
    protected OmsCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnOmsOrderItemStateTransfer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject([]));

        // Act
        $omsOrderItemStateTransfer = (new DefaultOrderItemInitialStateProviderPlugin())
            ->getInitialItemState($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->assertSame(static::STATE_NEW, $omsOrderItemStateTransfer->getName());
    }

    /**
     * @return void
     */
    public function testShouldNotReturnOmsOrderItemStateTransferForExistingOrderItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject([
            (new ItemTransfer())->setIdSalesOrderItem(1),
            (new ItemTransfer())->setIdSalesOrderItem(2),
        ]));

        // Act
        $omsOrderItemStateTransfer = (new DefaultOrderItemInitialStateProviderPlugin())
            ->getInitialItemState($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->assertNull($omsOrderItemStateTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnNullWhenQuoteHasMixedItemsWithAndWithoutSalesOrderIds(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject([
            (new ItemTransfer())->setIdSalesOrderItem(1),
            (new ItemTransfer())->setIdSalesOrderItem(null),
            (new ItemTransfer())->setIdSalesOrderItem(2),
        ]));

        // Act
        $omsOrderItemStateTransfer = (new DefaultOrderItemInitialStateProviderPlugin())
            ->getInitialItemState($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->assertNull($omsOrderItemStateTransfer);
    }
}
