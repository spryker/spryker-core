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
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ShipmentDiscountConnector
 * @group Business
 * @group Facade
 * @group ShipmentMethodDecisionRuleTest
 * Add your own group annotations below this line
 */
class ShipmentMethodDecisionRuleTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ShipmentDiscountConnector\ShipmentDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentMethodDecisionRuleShouldMatchDifferentShipmentMethodsDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string[] $expectedValues
     *
     * @return void
     */
    public function testShipmentMethodDecisionRuleShouldMatchDifferentShipmentMethods(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedValues
    ): void {
        // Arrange
        $actualMatchedItemSkuList = [];

        // Act
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $actualMatchedItemSkuList[$itemTransfer->getSku()] = $this->tester->getFacade()->isMethodSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
        }

        // Assert
        $i = 0;
        foreach ($actualMatchedItemSkuList as $sku => $isSatisfied) {
            $this->assertEquals(
                $expectedValues[$sku],
                $isSatisfied,
                sprintf('The actual item shipment\'s method does not satisfied the rule (iteration #%d).', $i++)
            );
        }
    }

    /**
     * @return array
     */
    public function shipmentMethodDecisionRuleShouldMatchDifferentShipmentMethodsDataProvider(): array
    {
        return [
            'Quote level shipment: 1 shipment, 1 method, Clause: shipment #1; expected: 1 shipment is matched' => $this->getDataWith1QuoteLevelShipmentAndMethodForSingleShipmentIsMatched(),
            'Item level shipment: 3 items, 2 shipments, Clause: shipment #1; expected: 1 shipment is matched' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForSingleShipmentIsMatched(),
            'Item level shipment: 3 items, 2 shipments, 2 methods, Clause: shipment #2; expected: 2 shipments are matched' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForMultipleShipmentIsMatched(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1QuoteLevelShipmentAndMethodForSingleShipmentIsMatched(): array
    {
        $shipmentBuilder = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 1]);
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment($shipmentBuilder)
            ->withItem()
            ->build();

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($quoteTransfer->getShipment()->getMethod());

        return [
            $quoteTransfer,
            $clauseTransfer,
            [$quoteTransfer->getItems()[0]->getSku() => true],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForSingleShipmentIsMatched(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 1])->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 2])->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($shipmentTransfer1->getMethod());

        return [
            $quoteTransfer,
            $clauseTransfer, [
                $itemTransfer1->getSku() => true,
                $itemTransfer2->getSku() => false,
                $itemTransfer3->getSku() => false,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForMultipleShipmentIsMatched(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 1])->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod(['idShipmentMethod' => 2])->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($shipmentTransfer2->getMethod());

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
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function addNewItemWithShipmentIntoQuoteTransfer(QuoteTransfer $quoteTransfer, ShipmentTransfer $shipmentTransfer): ItemTransfer
    {
        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransferWithShipmentMethod(ShipmentMethodTransfer $shipmentMethodTransfer): ClauseTransfer
    {
        return (new ClauseBuilder([
            ClauseTransfer::FIELD => 'getIdShipmentMethod',
            ClauseTransfer::VALUE => $shipmentMethodTransfer->getIdShipmentMethod(),
            ClauseTransfer::OPERATOR => '=',
            ClauseTransfer::ACCEPTED_TYPES => [ComparatorOperators::TYPE_NUMBER],
        ]))->build();
    }
}
