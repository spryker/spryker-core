<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group SanitizeOutdatedOrderItemsTest
 * Add your own group annotations below this line
 */
class SanitizeOutdatedOrderItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSanitizeOutdatedOrderItemsCleanUpsOutdatedOrderItems(): void
    {
        // Arrange
        $itemTransfers = [
            $this->buildItemTransferByCreatedAtTime('-39 day'),
            $this->buildItemTransferByCreatedAtTime('-30 day'),
            $this->buildItemTransferByCreatedAtTime('-12 day'),
            $this->buildItemTransferByCreatedAtTime('-4 day'),
        ];

        // Act
        $sanitizedItemTransfers = $this->tester
            ->getFacade()
            ->sanitizeOutdatedOrderItems(new ArrayObject($itemTransfers));

        // Assert
        $this->assertCount(2, $sanitizedItemTransfers);
    }

    /**
     * @return void
     */
    public function testSanitizeOutdatedOrderItemsWithoutCreateAtField(): void
    {
        // Arrange
        $itemTransfers = [
            new ItemTransfer(),
            $this->buildItemTransferByCreatedAtTime('-4 day'),
            new ItemTransfer(),
        ];

        // Act
        $sanitizedItemTransfers = $this->tester
            ->getFacade()
            ->sanitizeOutdatedOrderItems(new ArrayObject($itemTransfers));

        // Assert
        $this->assertCount(1, $sanitizedItemTransfers);
    }

    /**
     * @param string $time
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransferByCreatedAtTime(string $time = 'now'): ItemTransfer
    {
        return (new ItemTransfer())
            ->setCreatedAt((new DateTime($time))->format('Y-m-d'));
    }
}
