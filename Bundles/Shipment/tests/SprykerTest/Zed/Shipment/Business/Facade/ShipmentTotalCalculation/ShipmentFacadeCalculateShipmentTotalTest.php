<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Facade\ShipmentTotalCalculation;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

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
     * @see \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    protected const FAKE_EXPENSE_TYPE = 'FAKE_EXPENSE_TYPE';

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
        $calculableObjectTransfer = $this->createCalculableObjectWithFakeExpenses();

        // Act
        $this->tester->getFacade()->calculateShipmentTotal($calculableObjectTransfer);

        // Assert
        $this->assertSame(300, $calculableObjectTransfer->getTotals()->getShipmentTotal());
    }

    /**
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function createCalculableObjectWithFakeExpenses(): CalculableObjectTransfer
    {
        $quoteTransfer = (new QuoteTransfer())
            ->addExpense(
                (new ExpenseTransfer())
                ->setType(static::SHIPMENT_EXPENSE_TYPE)
                ->setSumPrice(100)
            )
            ->addExpense(
                (new ExpenseTransfer())
                ->setType(static::SHIPMENT_EXPENSE_TYPE)
                ->setSumPrice(200)
            )
            ->addExpense(
                (new ExpenseTransfer())
                ->setType(static::FAKE_EXPENSE_TYPE)
                ->setSumPrice(300)
            );

        return (new CalculableObjectTransfer())
            ->setOriginalQuote($quoteTransfer)
            ->setTotals(new TotalsTransfer());
    }
}
