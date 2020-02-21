<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn\Business\SalesReturnFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
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
 * @group CalculateRemunerationTotalTest
 * Add your own group annotations below this line
 */
class CalculateRemunerationTotalTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesReturn\SalesReturnBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCalculateRemunerationTotalCalculatesRemunerationTotal(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectWithFakeRemuneration();

        // Act
        $this->tester->getFacade()->calculateRemunerationTotal($calculableObjectTransfer);

        // Assert
        $this->assertSame(600, $calculableObjectTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testCalculateRemunerationTotalCalculatesExpansesWithoutExpenses(): void
    {
        // Arrange
        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setItems(new ArrayObject())
            ->setTotals(new TotalsTransfer());

        // Act
        $this->tester->getFacade()->calculateRemunerationTotal($calculableObjectTransfer);

        // Assert
        $this->assertSame(0, $calculableObjectTransfer->getTotals()->getRemunerationTotal());
    }

    /**
     * @return void
     */
    public function testCalculateRemunerationTotalThrowsExceptionWithEmptyTotals(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectWithFakeRemuneration();
        $calculableObjectTransfer->setTotals(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->calculateRemunerationTotal($calculableObjectTransfer);
    }
}
