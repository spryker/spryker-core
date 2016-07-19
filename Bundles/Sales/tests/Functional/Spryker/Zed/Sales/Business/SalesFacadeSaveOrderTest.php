<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Sales\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddressQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacade;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * @group Zed
 * @group Sales
 * @group Business
 * @group SalesFacadeTest
 */
class SalesFacadeSaveOrderTest extends Test
{

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacade
     */
    protected $salesFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $countryFacadeMock = $this->getMock(SalesToCountryInterface::class, ['getIdCountryByIso2Code', 'getAvailableCountries']);
        $countryFacadeMock->method('getIdCountryByIso2Code')
            ->will($this->returnValue(1));

        $omsOrderProcessEntity = $this->getProcessEntity();

        $omsFacadeMock = $this->getMock(
            SalesToOmsInterface::class,
            [
                'selectProcess',
                'getInitialStateEntity',
                'getProcessEntity',
                'getManualEvents',
                'getItemsWithFlag',
                'getManualEventsByIdSalesOrder',
                'getDistinctManualEventsByIdSalesOrder'
            ]
        );
        $omsFacadeMock->method('selectProcess')
            ->will($this->returnValue('CheckoutTest01'));

        $initialStateEntity = SpyOmsOrderItemStateQuery::create()
            ->filterByName(OmsConstants::INITIAL_STATUS)
            ->findOneOrCreate();
        $initialStateEntity->save();

        $omsFacadeMock->method('getInitialStateEntity')
            ->will($this->returnValue($initialStateEntity));

        $omsFacadeMock->method('getProcessEntity')
            ->will($this->returnValue($omsOrderProcessEntity));

        $sequenceNumberFacade = new SequenceNumberFacade();

        $container = new Container();
        $container[SalesDependencyProvider::FACADE_COUNTRY] = new SalesToCountryBridge($countryFacadeMock);
        $container[SalesDependencyProvider::FACADE_OMS] = new SalesToOmsBridge($omsFacadeMock);
        $container[SalesDependencyProvider::FACADE_SEQUENCE_NUMBER] = new SalesToSequenceNumberBridge($sequenceNumberFacade);

        $this->salesFacade = new SalesFacade();
        $businessFactory = new SalesBusinessFactory();
        $salesConfigMock = $this->getMock(SalesConfig::class, ['determineProcessForOrderItem']);
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn('');
        $businessFactory->setConfig($salesConfigMock);
        $businessFactory->setContainer($container);
        $this->salesFacade->setFactory($businessFactory);
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesBillingAddressAndAssignsItToOrder()
    {
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1('address-1-1-test')
            ->filterByFirstName('Max')
            ->filterByLastName('Mustermann')
            ->filterByZipCode('1337')
            ->filterByCity('SpryHome');

        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());

        $addressEntity = $salesOrderAddressQuery->findOne();

        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getBillingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    private function getValidBaseResponseTransfer()
    {
        return new CheckoutResponseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function getValidBaseQuoteTransfer()
    {
        $country = new SpyCountry();
        $country->setIso2Code('ix');
        $country->save();

        $quoteTransfer = new QuoteTransfer();
        $billingAddress = new AddressTransfer();

        $billingAddress->setIso2Code('ix')
            ->setAddress1('address-1-1-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $shippingAddress = new AddressTransfer();
        $shippingAddress->setIso2Code('ix')
            ->setAddress1('address-1-2-test')
            ->setFirstName('Max')
            ->setLastName('Mustermann')
            ->setZipCode('1337')
            ->setCity('SpryHome');

        $totals = new TotalsTransfer();
        $totals->setGrandTotal(1337)
            ->setSubtotal(337);

        $quoteTransfer->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setTotals($totals);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail('max@mustermann.de');
        $customerTransfer->setFirstName('Max');
        $customerTransfer->setLastName('Mustermann');

        $quoteTransfer->setCustomer($customerTransfer);

        $shipmentTransfer = new ShipmentTransfer();
        $shipmentTransfer->setMethod(new ShipmentMethodTransfer());
        $quoteTransfer->setShipment($shipmentTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setUnitGrossPrice(1)
            ->setQuantity(1)
            ->setName('test-name')
            ->setSku('sku-test');
        $quoteTransfer->addItem($itemTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPaymentSelection('dummyPaymentInvoice');

        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesShippingAddressAndAssignsItToOrder()
    {
        $salesOrderAddressQuery = SpySalesOrderAddressQuery::create()
            ->filterByAddress1('address-1-2-test')
            ->filterByFirstName('Max')
            ->filterByLastName('Mustermann')
            ->filterByCity('SpryHome');

        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $this->salesFacade->saveOrder($quoteTransfer, $this->getValidBaseResponseTransfer());

        $addressEntity = $salesOrderAddressQuery->findOne();

        $this->assertNotNull($addressEntity);
        $this->assertSame($addressEntity->getIdSalesOrderAddress(), $quoteTransfer->getShippingAddress()
            ->getIdSalesOrderAddress());
    }

    /**
     * @return void
     */
    public function testSaveOrderAssignsSavedOrderId()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesOrderAndSavesFields()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $orderQuery = SpySalesOrderQuery::create()
            ->filterByPrimaryKey($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder());

        $orderEntity = $orderQuery->findOne();
        $this->assertNotNull($orderEntity);

        $this->assertSame('max@mustermann.de', $orderEntity->getEmail());
        $this->assertSame('Max', $orderEntity->getFirstName());
        $this->assertSame('Mustermann', $orderEntity->getLastName());
    }

    /**
     * @return void
     */
    public function testSaveOrderCreatesAndFillsOrderItems()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();

        $initialState = SpyOmsOrderItemStateQuery::create()
            ->filterByName(OmsConstants::INITIAL_STATUS)
            ->findOneOrCreate();
        $initialState->save();

        $this->assertNotNull($initialState->getIdOmsOrderItemState());

        $item1 = new ItemTransfer();
        $item1->setName('item-test-1')
            ->setSku('sku1')
            ->setUnitGrossPrice(120)
            ->setQuantity(2)
            ->setTaxRate(19);

        $item2 = new ItemTransfer();
        $item2->setName('item-test-2')
            ->setSku('sku2')
            ->setUnitGrossPrice(130)
            ->setQuantity(3)
            ->setTaxRate(19);

        $quoteTransfer->addItem($item1);
        $quoteTransfer->addItem($item2);

        $item1Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-1');

        $item2Query = SpySalesOrderItemQuery::create()
            ->filterByName('item-test-2');

        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);

        $savedItems = $checkoutResponseTransfer->getSaveOrder()->getOrderItems();

        $item1Entity = $item1Query->findOne();
        $item2Entity = $item2Query->findOne();

        $this->assertNotNull($item1Entity);
        $this->assertNotNull($item2Entity);

        $this->assertSame($savedItems[1]->getIdSalesOrderItem(), $item1Entity->getIdSalesOrderItem());
        $this->assertSame($item1->getName(), $item1Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item1Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item1Entity->getFkOmsOrderItemState());
        $this->assertSame($item1->getSku(), $item1Entity->getSku());
        $this->assertSame($savedItems[1]->getUnitGrossPrice(), $item1Entity->getGrossPrice());
        $this->assertSame(1, $item1Entity->getQuantity());

        $this->assertSame($savedItems[3]->getIdSalesOrderItem(), $item2Entity->getIdSalesOrderItem());
        $this->assertSame($item2->getName(), $item2Entity->getName());
        $this->assertSame($checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder(), $item2Entity->getFkSalesOrder());
        $this->assertSame($initialState->getIdOmsOrderItemState(), $item2Entity->getFkOmsOrderItemState());
        $this->assertSame($item2->getSku(), $item2Entity->getSku());
        $this->assertSame($savedItems[3]->getUnitGrossPrice(), $item2Entity->getGrossPrice());
        $this->assertSame(1, $item2Entity->getQuantity());
    }

    /**
     * @return void
     */
    public function testSaveOrderGeneratesOrderReference()
    {
        $quoteTransfer = $this->getValidBaseQuoteTransfer();
        $checkoutResponseTransfer = $this->getValidBaseResponseTransfer();
        $this->salesFacade->saveOrder($quoteTransfer, $checkoutResponseTransfer);
        $this->assertNotNull($checkoutResponseTransfer->getSaveOrder()->getOrderReference());
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity()
    {
        $omsOrderProcessEntity = (new SpyOmsOrderProcessQuery())->filterByName('CheckoutTest01')->findOneOrCreate();
        $omsOrderProcessEntity->save();

        return $omsOrderProcessEntity;
    }

}
