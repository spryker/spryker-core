<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\MailHandler;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\Shipment\Communication\Plugin\OrderShipmentSavePlugin;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentOrderHydratePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group MailHandler
 * @group MailHandlerTest
 * Add your own group annotations below this line
 */
class MailHandlerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [
                new ShipmentOrderHydratePlugin(),
            ]
        );
    }

    /**
     * @dataProvider sendOrderConfirmationMailShouldPrepareCorrectMailTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testSendOrderConfirmationMailShouldPrepareCorrectMailTransfer(QuoteTransfer $quoteTransfer)
    {
        // Arrange
        $saveOrderTransfer = $this->tester->createOrderWithShipment($quoteTransfer, 'Test01', [new OrderShipmentSavePlugin()]);
        $salesOrderEntity = SpySalesOrderQuery::create()->filterByIdSalesOrder($saveOrderTransfer->getIdSalesOrder())->findOne();

        // Act
        /**
         * @todo Debug why emails are not sending.
         */
        $this->tester->getFacade()->sendOrderConfirmationMail($salesOrderEntity);

        // Assert
        /**
         * @todo Add some assertion.
         */
    }

    /**
     * @return array
     */
    public function sendOrderConfirmationMailShouldPrepareCorrectMailTransferDataProvider(): array
    {
        return [
            'France 1 item, 1 address; expected: 1 order shipment with shipping address in DB' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(): array
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => 'FR']));

        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        $itemBuilder = (new ItemBuilder())
            ->withShipment($shipmentBuilder);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder)
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer];
    }
}
