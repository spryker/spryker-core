<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentDiscountConnector\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ClauseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentCarrierBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentCarrierTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group ShipmentCarrierDecisionRuleTest
 * Add your own group annotations below this line
 */
class ShipmentCarrierDecisionRuleTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentCarrierDecisionRuleShouldMatchDifferentShipmentCarriersDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string[] $expectedValues
     *
     * @return void
     */
    public function testShipmentCarrierDecisionRuleShouldMatchDifferentShipmentCarriers(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange
        $actualMatchedItemSkuList = [];

        // Act
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $actualMatchedItemSkuList[$itemTransfer->getSku()] = $this->tester->getFacade()->isCarrierSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        }

        // Assert
        $i = 0;
        foreach ($actualMatchedItemSkuList as $sku => $isSatisfied) {
            $this->assertEquals(
                $expectedValues[$sku],
                $isSatisfied,
                sprintf('The actual item shipment\'s carrier does not satisfied the rule (iteration #%d).', $i++)
            );
        }
    }

    /**
     * @return array
     */
    public function shipmentCarrierDecisionRuleShouldMatchDifferentShipmentCarriersDataProvider(): array
    {
        return [
            'Quote level shipment: 1 shipment, 1 carrier, Clause: carrier #1; expected: 1 carrier is matched' => $this->getDataWith1QuoteLevelShipmentAndCarrierForSingleCarrierIsMatched(),
            'Item level shipment: 3 items, 3 shipments, 2 carriers, Clause: carrier #1; expected: 1 carrier is matched' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndCarriersForSingleCarrierIsMatched(),
            'Item level shipment: 3 items, 3 shipments, 2 carriers, Clause: carrier #2; expected: 2 carriers are matched' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndCarriersForMultipleCarrierIsMatched(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1QuoteLevelShipmentAndCarrierForSingleCarrierIsMatched(): array
    {
        $shipmentBuilder = (new ShipmentBuilder())->withCarrier();
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentBuilder)
            ->withItem()
            ->build();

        $clauseTransfer = $this->createClauseTransferWithShipmentCarrier($quoteTransfer->getShipment()->getCarrier());

        return [
            $quoteTransfer,
            $clauseTransfer,
            [$quoteTransfer->getItems()[0]->getSku() => true],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndCarriersForSingleCarrierIsMatched(): array
    {
        $shipmentCarrierTransfer1 = (new ShipmentCarrierBuilder())->build();
        $shipmentCarrierTransfer2 = (new ShipmentCarrierBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentCarrier($shipmentCarrierTransfer1);

        return [
            $quoteTransfer,
            $clauseTransfer,
            [
                $itemTransfer1->getSku() => true,
                $itemTransfer2->getSku() => false,
                $itemTransfer3->getSku() => false,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndCarriersForMultipleCarrierIsMatched(): array
    {
        $shipmentCarrierTransfer1 = (new ShipmentCarrierBuilder())->build();
        $shipmentCarrierTransfer2 = (new ShipmentCarrierBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentCarrierIntoQuoteTransfer($quoteTransfer, $shipmentCarrierTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentCarrier($shipmentCarrierTransfer2);

        return [
            $quoteTransfer,
            $clauseTransfer,
            [
                $itemTransfer1->getSku() => false,
                $itemTransfer2->getSku() => true,
                $itemTransfer3->getSku() => true,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addNewItemWithShipmentCarrierIntoQuoteTransfer(QuoteTransfer $quoteTransfer, ShipmentCarrierTransfer $shipmentCarrierTransfer): ItemTransfer
    {
        $itemTransfer = (new ItemBuilder())
            ->withShipment()
            ->build();
        $itemTransfer->getShipment()->setCarrier($shipmentCarrierTransfer);

        $quoteTransfer->addItem($itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransferWithShipmentCarrier(ShipmentCarrierTransfer $shipmentCarrierTransfer): ClauseTransfer
    {
        return (new ClauseBuilder([
            ClauseTransfer::FIELD => 'getIdShipmentMethod',
            ClauseTransfer::VALUE => $shipmentCarrierTransfer->getIdShipmentCarrier(),
            ClauseTransfer::OPERATOR => '=',
            ClauseTransfer::ACCEPTED_TYPES => [ComparatorOperators::TYPE_NUMBER],
        ]))->build();
    }
}
