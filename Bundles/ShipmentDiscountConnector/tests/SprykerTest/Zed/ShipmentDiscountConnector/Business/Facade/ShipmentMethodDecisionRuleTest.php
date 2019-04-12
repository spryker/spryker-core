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
     * @param string[] $expectedMatchedItemSkuList
     *
     * @return void
     */
    public function testShipmentMethodDecisionRuleShouldMatchDifferentShipmentMethods(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer,
        array $expectedMatchedItemSkuList
    ): void {
        // Arrange
        $actualMatchedItemSkuList = [];

        // Act
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $isRuleMatched = $this->tester->getFacade()->isMethodSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
            if (!$isRuleMatched) {
                continue;
            }

            $actualMatchedItemSkuList[] = $itemTransfer->getSku();
        }

        // Assert
        $this->assertCount(count($expectedMatchedItemSkuList), $actualMatchedItemSkuList, 'Actual and expected rule matches counts are not the same.');

        foreach ($actualMatchedItemSkuList as $i => $sku) {
            $this->assertContains($sku, $expectedMatchedItemSkuList, sprintf('Actual and expected rule decisions do not match (iteration #%d).', $i));
        }
    }

    /**
     * @return array
     */
    public function shipmentMethodDecisionRuleShouldMatchDifferentShipmentMethodsDataProvider(): array
    {
        return [
            'Quote level shipment: 1 shipment, 1 method, Clause: shipment #1; expected: 1 shipment matches' => $this->getDataWith1QuoteLevelShipmentAndMethodForSingleShipmentIsMatched(),
            'Item level shipment: 3 items, 2 shipments, Clause: shipment #1; expected: 1 shipment matches' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForSingleShipmentIsMatched(),
            'Item level shipment: 3 items, 2 shipments, 2 methods, Clause: shipment #2; expected: 2 shipments match' => $this->getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForMultipleShipmentIsMatched(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWith1QuoteLevelShipmentAndMethodForSingleShipmentIsMatched(): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withShipment((new ShipmentBuilder())->withMethod())
            ->withItem()
            ->build();

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($quoteTransfer->getShipment()->getMethod());

        return [$quoteTransfer, $clauseTransfer, [$quoteTransfer->getItems()[0]->getSku()]];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForSingleShipmentIsMatched(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod()->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod()->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($shipmentTransfer1->getMethod());

        return [$quoteTransfer, $clauseTransfer, [$itemTransfer1->getSku()]];
    }

    /**
     * @return array
     */
    protected function getDataWith3ItemsAnd2ItemLevelShipmentsAndMethodsForMultipleShipmentIsMatched(): array
    {
        $shipmentTransfer1 = (new ShipmentBuilder())->withMethod()->build();
        $shipmentTransfer2 = (new ShipmentBuilder())->withMethod()->build();

        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer1 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer1);
        $itemTransfer2 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);
        $itemTransfer3 = $this->addNewItemWithShipmentIntoQuoteTransfer($quoteTransfer, $shipmentTransfer2);

        $clauseTransfer = $this->createClauseTransferWithShipmentMethod($shipmentTransfer2->getMethod());

        return [$quoteTransfer, $clauseTransfer, [$itemTransfer2->getSku(), $itemTransfer3->getSku()]];
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
