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
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Mail\CompanyUserInvitationMailTypePlugin;
use Spryker\Zed\Mail\Business\Model\Mail\MailTypeCollection;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection;
use Spryker\Zed\Mail\Communication\Plugin\MailProviderPlugin;
use Spryker\Zed\Mail\MailConfig;
use Spryker\Zed\Mail\MailDependencyProvider;
use Spryker\Zed\Oms\Communication\Plugin\Mail\OrderConfirmationMailTypePlugin;
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

        $mailTypeCollection = new MailTypeCollection();
        $mailTypeCollection->add(new OrderConfirmationMailTypePlugin());
        $this->tester->setDependency(MailDependencyProvider::MAIL_TYPE_COLLECTION, $mailTypeCollection);

        $mailProviderCollection = new MailProviderCollection();
        $mailProviderCollection->addProvider(new MailProviderPlugin(), [
            MailConfig::MAIL_TYPE_ALL,
            CompanyUserInvitationMailTypePlugin::MAIL_TYPE,
        ]);
        $this->tester->setDependency(MailDependencyProvider::MAIL_PROVIDER_COLLECTION, $mailProviderCollection);

        /**
         * @todo Set up dry run for email sender.
         */
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
            'France 1 item; expected: 1 shipment in email' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(),
            /**
             * @todo Uncomment when will be fixed shipment saving for multiple items.
             */
//            'France 1 item, Germany 1 item; expected: 2 shipments in email' => $this->getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFranceAnd1ItemToGermany(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFrance(): array
    {
        $itemBuilder1 = $this->getPreparedItemBuilder('FR');

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder1)
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer];
    }

    /**
     * @return array
     */
    protected function getDataWithMultipleShipmentsAndShippingAddressesAnd1ItemToFranceAnd1ItemToGermany(): array
    {
        $itemBuilder1 = $this->getPreparedItemBuilder('FR');
        $itemBuilder2 = $this->getPreparedItemBuilder('DE');

        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemBuilder1)
            ->withAnotherItem($itemBuilder2)
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        return [$quoteTransfer];
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\DataBuilder\ItemBuilder
     */
    protected function getPreparedItemBuilder(string $iso2Code): ItemBuilder
    {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => $iso2Code]));
        $shipmentBuilder = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->withMethod();

        return (new ItemBuilder())
            ->withShipment($shipmentBuilder);
    }
}
