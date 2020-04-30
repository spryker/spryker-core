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

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesReturn
 * @group Business
 * @group SalesReturnFacade
 * @group ExpandOrderTotalsWithRemunerationTotalTest
 * Add your own group annotations below this line
 */
class ExpandOrderTotalsWithRemunerationTotalTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandOrderTotalsWithRemunerationTotalExpandsRemunerationTotal(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithFakeRemuneration();

        // Act
        $this->tester->getFacade()->expandOrderTotalsWithRemunerationTotal($orderTransfer);

        // Assert
        $this->assertSame(600, $orderTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testExpandOrderTotalsWithRemunerationTotalWithoutItems(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setItems(new ArrayObject())
            ->setTotals(new TotalsTransfer());

        // Act
        $this->tester->getFacade()->expandOrderTotalsWithRemunerationTotal($orderTransfer);

        // Assert
        $this->assertSame(0, $orderTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testExpandOrderTotalsWithRemunerationTotalWithoutTotal(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderWithFakeRemuneration();
        $orderTransfer->setTotals(null);

        // Act
        $this->tester->getFacade()->expandOrderTotalsWithRemunerationTotal($orderTransfer);

        // Assert
        $this->assertSame(600, $orderTransfer->getTotals()->getRemunerationTotal());
    }
}
