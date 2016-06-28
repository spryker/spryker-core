<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Shipmwent\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;

/**
 * @group ShipmentTaxRate
 */
class ShipmentTaxRateCalculationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCalculateTaxRateForDefaultCountry()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockDefaultTaxRates());
        $this->assertEquals(19, $taxAverage);
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForDifferentCountry()
    {
        $quoteTransfer = $this->createQuoteTransfer();

        $taxAverage = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $this->getMockCountryBasedTaxRates());
        $this->assertEquals(12, $taxAverage);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, $mockData)
    {
        $productItemTaxRateCalculatorMock = $this->createShipmentTaxRateCalculator();
        $productItemTaxRateCalculatorMock->method('findTaxSetByIdShipmentMethodAndCountry')->willReturn($mockData);

        $productItemTaxRateCalculatorMock->recalculate($quoteTransfer);
        $taxAverage = $this->getExpenseItemsTaxRateAverage($quoteTransfer);

        return $taxAverage;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    protected function createShipmentTaxRateCalculator()
    {
        return $productItemTaxRateCalculatorMock = $this->getMock(ShipmentTaxRateCalculator::class, ['findTaxSetByIdShipmentMethodAndCountry'], [
            $this->createQueryContainerMock(),
            $this->createProductOptionToTaxBridgeMock(),
        ]);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface
     */
    protected function createQueryContainerMock()
    {
        return $this->getMockBuilder(ShipmentQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge
     */
    protected function createProductOptionToTaxBridgeMock()
    {
        $bridgeMock = $this->getMockBuilder(ShipmentToTaxBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxCountry')
            ->willReturn('DE');

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxRate')
            ->willReturn(19);

        return $bridgeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return float
     */
    protected function getExpenseItemsTaxRateAverage(QuoteTransfer $quoteTransfer)
    {
        $taxSum = 0;
        foreach ($quoteTransfer->getExpenses() as $expense) {
            $taxSum += $expense->getTaxRate();
        }

        $taxAverage = $taxSum / count($quoteTransfer->getExpenses());

        return $taxAverage;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        $quoteTransfer =  new QuoteTransfer();

        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->setName('DummyShipment');

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $quoteTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setName($shipmentMethodTransfer->getName());
        $expenseTransfer->setType(ShipmentConstants::SHIPMENT_EXPENSE_TYPE);

        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    protected function getMockDefaultTaxRates()
    {
        return null;
    }

    /**
     * @return array
     */
    protected function getMockCountryBasedTaxRates()
    {
        return [
            ShipmentQueryContainer::COL_SUM_TAX_RATE => 12,
        ];
    }

}
