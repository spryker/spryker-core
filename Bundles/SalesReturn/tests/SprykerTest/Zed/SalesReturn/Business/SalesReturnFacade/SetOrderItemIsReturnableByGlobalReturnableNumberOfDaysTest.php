<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

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
 * @group SetOrderItemIsReturnableByGlobalReturnableNumberOfDaysTest
 * Add your own group annotations below this line
 */
class SetOrderItemIsReturnableByGlobalReturnableNumberOfDaysTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByGlobalReturnableNumberOfDays(): void
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
            ->setOrderItemIsReturnableByGlobalReturnableNumberOfDays($itemTransfers);

        // Assert
        $this->assertFalse($sanitizedItemTransfers[0]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[1]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[2]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[3]->getIsReturnable());
    }

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByGlobalReturnableNumberOfDaysWithoutCreateAtField(): void
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
            ->setOrderItemIsReturnableByGlobalReturnableNumberOfDays($itemTransfers);

        // Assert
        $this->assertFalse($sanitizedItemTransfers[0]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[1]->getIsReturnable());
        $this->assertFalse($sanitizedItemTransfers[2]->getIsReturnable());
    }

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByGlobalReturnableNumberOfDaysReturnPolicyMessage(): void
    {
        // Arrange
        $itemTransfers = [
            new ItemTransfer(),
            $this->buildItemTransferByCreatedAtTime('-4 day'),
            $this->buildItemTransferByCreatedAtTime('-50 day'),
        ];

        // Act
        $sanitizedItemTransfers = $this->tester
            ->getFacade()
            ->setOrderItemIsReturnableByGlobalReturnableNumberOfDays($itemTransfers);

        // Assert
        $this->assertEmpty($sanitizedItemTransfers[0]->getReturnPolicyMessages());
        $this->assertNotEmpty($sanitizedItemTransfers[1]->getReturnPolicyMessages());
        $this->assertNotEmpty($sanitizedItemTransfers[2]->getReturnPolicyMessages());
    }

    /**
     * @param string $time
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransferByCreatedAtTime(string $time = 'now'): ItemTransfer
    {
        return (new ItemTransfer())
            ->setIsReturnable(true)
            ->setCreatedAt((new DateTime($time))->format('Y-m-d'));
    }
}
