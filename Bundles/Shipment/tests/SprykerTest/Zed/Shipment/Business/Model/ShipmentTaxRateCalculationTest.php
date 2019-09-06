<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentCarrier;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Pyz\Shared\Shipment\ShipmentConfig;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Business\ShipmentFacade;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Business
 * @group Model
 * @group ShipmentTaxRateCalculationTest
 * Add your own group annotations below this line
 */
class ShipmentTaxRateCalculationTest extends Unit
{
    public const DEFAULT_TAX_RATE = 19;
    public const DEFAULT_TAX_COUNTRY = 'DE';

    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedShouldSetZeroTaxRate()
    {
        $shipmentMethodEntity = $this->createShipmentMethodWithTaxSet(20, 'DE');

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code('GB');
        $quoteTransfer->setShippingAddress($addressTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);

        $shipmentFacadeTest = $this->createShipmentFacade();
        $shipmentFacadeTest->calculateShipmentTaxRate($quoteTransfer);

        $this->assertEquals('0.0', $shipmentMethodTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testSetTaxRateWhenExemptTaxRateUsedAndCountryMatchingShouldUseCountryRate()
    {
        $shipmentMethodEntity = $this->createShipmentMethodWithTaxSet(20, 'DE');

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code('DE');
        $quoteTransfer->setShippingAddress($addressTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentTransfer->setMethod($shipmentMethodTransfer);
        $quoteTransfer->setShipment($shipmentTransfer);

        $shipmentFacadeTest = $this->createShipmentFacade();
        $shipmentFacadeTest->calculateShipmentTaxRate($quoteTransfer);

        $this->assertEquals('20.00', $shipmentMethodTransfer->getTaxRate());
    }

    /**
     * @param int $taxRate
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod
     */
    protected function createShipmentMethodWithTaxSet($taxRate, $iso2Code)
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity1 = new SpyTaxRate();
        $taxRateEntity1->setRate($taxRate);
        $taxRateEntity1->setName('test rate 1');
        $taxRateEntity1->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity1->save();

        $taxRateEntity2 = new SpyTaxRate();
        $taxRateEntity2->setRate(13);
        $taxRateEntity2->setName('tax rate 2');
        $taxRateEntity2->setFkCountry($countryEntity->getIdCountry());
        $taxRateEntity2->save();

        $taxRateExemptEntity = new SpyTaxRate();
        $taxRateExemptEntity->setRate(0);
        $taxRateExemptEntity->setName(TaxConstants::TAX_EXEMPT_PLACEHOLDER);
        $taxRateExemptEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('name of tax set');
        $taxSetEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity1->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity2->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateExemptEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        $shipmentCarrierEntity = new SpyShipmentCarrier();
        $shipmentCarrierEntity->setName('name carrier');
        $shipmentCarrierEntity->save();

        $shipmentMethodEntity = new SpyShipmentMethod();
        $shipmentMethodEntity->setFkShipmentCarrier($shipmentCarrierEntity->getIdShipmentCarrier());
        $shipmentMethodEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $shipmentMethodEntity->setName('test shipment method');
        $shipmentMethodEntity->save();

        return $shipmentMethodEntity;
    }

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacade
     */
    protected function createShipmentFacade()
    {
        return new ShipmentFacade();
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForDefaultCountry()
    {
        // Assign
        $quoteTransfer = $this->createQuoteTransfer();
        $expectedResult = static::DEFAULT_TAX_RATE;

        // Act
        $actualResult = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, null);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForDifferentCountry()
    {
        // Assign
        $quoteTransfer = $this->createQuoteTransfer();
        $expectedResult = 12;

        // Act
        $actualResult = $this->getEffectiveTaxRateByQuoteTransfer($quoteTransfer, $expectedResult);

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /***
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param float|null $countryTaxRate
     *
     * @return float
     */
    protected function getEffectiveTaxRateByQuoteTransfer(QuoteTransfer $quoteTransfer, $countryTaxRate)
    {
        $productItemTaxRateCalculatorMock = $this->createShipmentTaxRateCalculator($countryTaxRate);

        $productItemTaxRateCalculatorMock->recalculate($quoteTransfer);
        $taxAverage = $this->getExpenseItemsTaxRateAverage($quoteTransfer);

        return $taxAverage;
    }

    /**
     * @param float|null $countryTaxRate
     *
     * @return \Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    protected function createShipmentTaxRateCalculator($countryTaxRate)
    {
        return new ShipmentTaxRateCalculator(
            $this->createQueryContainerMock($countryTaxRate),
            $this->createProductOptionToTaxBridgeMock(),
            $this->tester->getShipmentService()
        );
    }

    /**
     * @param float|null $countryTaxRate
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Shipment\Persistence\ShipmentQueryContainer
     */
    protected function createQueryContainerMock($countryTaxRate)
    {
        $return = $countryTaxRate === null ? null : [ShipmentQueryContainer::COL_MAX_TAX_RATE => $countryTaxRate];

        $queryMock = $this->getMockBuilder(SpyShipmentMethodQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock
            ->expects($this->any())
            ->method('findOne')
            ->willReturn($return);

        $queryContainerMock = $this->getMockBuilder(ShipmentQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryContainerMock
            ->expects($this->any())
            ->method('queryTaxSetByIdShipmentMethodAndCountryIso2Code')
            ->willReturn($queryMock);

        return $queryContainerMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge
     */
    protected function createProductOptionToTaxBridgeMock()
    {
        $bridgeMock = $this->getMockBuilder(ShipmentToTaxBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxCountryIso2Code')
            ->willReturn(static::DEFAULT_TAX_COUNTRY);

        $bridgeMock
            ->expects($this->any())
            ->method('getDefaultTaxRate')
            ->willReturn(static::DEFAULT_TAX_RATE);

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
        $quoteTransfer = new QuoteTransfer();

        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer->setName('DummyShipment');

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $quoteTransfer->setShipment($shipmentTransfer);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setName($shipmentMethodTransfer->getName());
        $expenseTransfer->setType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);

        $quoteTransfer->addExpense($expenseTransfer);

        return $quoteTransfer;
    }
}
