<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\ShipmentTotalCalculation;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Facade
 * @group ShipmentTotalCalculation
 * @group Facade
 * @group ShipmentFacadeCalculateShipmentTotalTest
 * Add your own group annotations below this line
 */
class ShipmentFacadeCalculateShipmentTotalTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCalculateShipmentTotalCalculatesExpansesWithShipmentType(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectWithFakeExpenses();

        // Act
        $this->tester->getFacade()->calculateShipmentTotal($calculableObjectTransfer);

        // Assert
        $this->assertSame(300, $calculableObjectTransfer->getTotals()->getShipmentTotal());
    }

    /**
     * @return void
     */
    public function testCalculateShipmentTotalCalculatesExpansesWithoutExpenses(): void
    {
        // Arrange
        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setExpenses(new ArrayObject())
            ->setTotals(new TotalsTransfer());

        // Act
        $this->tester->getFacade()->calculateShipmentTotal($calculableObjectTransfer);

        // Assert
        $this->assertSame(0, $calculableObjectTransfer->getTotals()->getShipmentTotal());
    }

    /**
     * @return void
     */
    public function testCalculateShipmentTotalThrowsExceptionWithEmptyTotals(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->createCalculableObjectWithFakeExpenses();
        $calculableObjectTransfer->setTotals(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->calculateShipmentTotal($calculableObjectTransfer);
    }
}
