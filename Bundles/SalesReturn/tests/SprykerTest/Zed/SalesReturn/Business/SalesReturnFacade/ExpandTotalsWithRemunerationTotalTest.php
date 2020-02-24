<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group ExpandTotalsWithRemunerationTotalTest
 * Add your own group annotations below this line
 */
class ExpandTotalsWithRemunerationTotalTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandTotalsWithRemunerationTotalExpandsRemunerationTotal(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithFakeRemuneration();

        // Act
        $this->tester->getFacade()->expandTotalsWithRemunerationTotal($orderTransfer);

        // Assert
        $this->assertSame(600, $orderTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testExpandTotalsWithRemunerationTotalWithoutItems(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject())
            ->setTotals(new TotalsTransfer());

        // Act
        $this->tester->getFacade()->expandTotalsWithRemunerationTotal($orderTransfer);

        // Assert
        $this->assertSame(0, $orderTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testExpandTotalsWithRemunerationTotalThrowsExceptionWithEmptyTotals(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithFakeRemuneration();
        $orderTransfer->setTotals(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->expandTotalsWithRemunerationTotal($orderTransfer);
    }
}
