<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Communication\Plugin\SalesOrderAmendment;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Shipment\Business\ShipmentBusinessFactory;
use Spryker\Zed\Shipment\Communication\Plugin\SalesOrderAmendment\ShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin;
use SprykerTest\Zed\Shipment\ShipmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Communication
 * @group Plugin
 * @group SalesOrderAmendment
 * @group ShipmentGroupsSalesOrderAmendmentQuoteExpanderPluginTest
 * Add your own group annotations below this line
 */
class ShipmentGroupsSalesOrderAmendmentQuoteExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Shipment\ShipmentCommunicationTester
     */
    protected ShipmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldExpandSalesOrderAmendmentQuotesWithShipmentGroups(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteTransfer = (new SalesOrderAmendmentQuoteTransfer())
            ->setQuote(
                (new QuoteTransfer())->addItem(new ItemTransfer()),
            );

        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote($salesOrderAmendmentQuoteTransfer);

        // Act
        $salesOrderAmendmentQuoteCollectionTransferExpanded = $this->createShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin()
            ->expand($salesOrderAmendmentQuoteCollectionTransfer);

        // Assert
        $this->assertCount(1, $salesOrderAmendmentQuoteCollectionTransferExpanded->getSalesOrderAmendmentQuotes());
        $this->assertCount(
            1,
            $salesOrderAmendmentQuoteCollectionTransferExpanded->getSalesOrderAmendmentQuotes()
                ->offsetGet(0)
                ->getShipmentGroups(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldThrowExceptionWhenQuoteIsNotSet(): void
    {
        // Arrange
        $salesOrderAmendmentQuoteCollectionTransfer = (new SalesOrderAmendmentQuoteCollectionTransfer())
            ->addSalesOrderAmendmentQuote(new SalesOrderAmendmentQuoteTransfer());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer` is null.');

        // Act
        $this->createShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin()
            ->expand($salesOrderAmendmentQuoteCollectionTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Shipment\Business\ShipmentBusinessFactory
     */
    protected function createShipmentBusinessFactoryMock(): ShipmentBusinessFactory
    {
        $factoryMock = $this->getMockBuilder(ShipmentBusinessFactory::class)->getMock();
        $factoryMock->method('getShipmentService')->willReturn($this->createShipmentServiceMock());

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected function createShipmentServiceMock(): ShipmentServiceInterface
    {
        $serviceMock = $this->getMockBuilder(ShipmentServiceInterface::class)->getMock();
        $serviceMock->method('groupItemsByShipment')->willReturn(new ArrayObject([new ShipmentGroupTransfer()]));

        return $serviceMock;
    }

    /**
     * @return \Spryker\Zed\Shipment\Communication\Plugin\SalesOrderAmendment\ShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin
     */
    protected function createShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin(): ShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin
    {
        $plugin = new ShipmentGroupsSalesOrderAmendmentQuoteExpanderPlugin();
        $plugin->setBusinessFactory($this->createShipmentBusinessFactoryMock());

        return $plugin;
    }
}
