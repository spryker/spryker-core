<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group SetOrderItemIsReturnableByItemStateTest
 * Add your own group annotations below this line
 */
class SetOrderItemIsReturnableByItemStateTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByItemState(): void
    {
        // Arrange
        $itemTransfers = [
            $this->buildItemTransferByState('new'),
            $this->buildItemTransferByState('paid'),
            $this->buildItemTransferByState('shipped'),
            $this->buildItemTransferByState('delivered'),
        ];

        // Act
        $sanitizedItemTransfers = $this->tester
            ->getFacade()
            ->setOrderItemIsReturnableByItemState($itemTransfers);

        // Assert
        $this->assertFalse($sanitizedItemTransfers[0]->getIsReturnable());
        $this->assertFalse($sanitizedItemTransfers[1]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[2]->getIsReturnable());
        $this->assertTrue($sanitizedItemTransfers[3]->getIsReturnable());
    }

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByItemStateThrowsExceptionWithoutItemState(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->setOrderItemIsReturnableByItemState([new ItemTransfer()]);
    }

    /**
     * @return void
     */
    public function testSetOrderItemIsReturnableByItemStateThrowsExceptionWithoutItemStateName(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester
            ->getFacade()
            ->setOrderItemIsReturnableByItemState([(new ItemTransfer())->setState(new ItemStateTransfer())]);
    }

    /**
     * @param string|null $stateName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildItemTransferByState(?string $stateName = null): ItemTransfer
    {
        return (new ItemTransfer())
            ->setIsReturnable(true)
            ->setState((new ItemStateTransfer())->setName($stateName));
    }
}
