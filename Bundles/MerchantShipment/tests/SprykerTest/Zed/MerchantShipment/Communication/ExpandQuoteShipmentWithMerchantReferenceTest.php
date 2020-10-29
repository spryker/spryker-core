<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment\Communication;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantShipment
 * @group Communication
 * @group ExpandQuoteShipmentWithMerchantReferenceTest
 * Add your own group annotations below this line
 */
class ExpandQuoteShipmentWithMerchantReferenceTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantShipment\MerchantShipmentCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandQuoteShipmentWithMerchantReferenceExpandsItemShipmentWithMerchantReference(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()))
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()));

        // Act
        $expandedQuoteTransfer = $this->tester->getShipmentExpander()
            ->expandQuoteShipmentWithMerchantReference($quoteTransfer);

        // Assert
        $this->assertSame(
            $quoteTransfer->getItems()->offsetGet(0)->getMerchantReference(),
            $expandedQuoteTransfer->getItems()->offsetGet(0)->getShipment()->getMerchantReference()
        );
        $this->assertSame(
            $quoteTransfer->getItems()->offsetGet(1)->getMerchantReference(),
            $expandedQuoteTransfer->getItems()->offsetGet(1)->getShipment()->getMerchantReference()
        );
    }

    /**
     * @return void
     */
    public function testExpandQuoteShipmentWithMerchantReferenceExpandsItemShipmentWithoutShipment(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemBuilder())->build())
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()));

        // Act
        $expandedQuoteTransfer = $this->tester->getShipmentExpander()
            ->expandQuoteShipmentWithMerchantReference($quoteTransfer);

        // Assert
        $this->assertNull($expandedQuoteTransfer->getItems()->offsetGet(0)->getShipment());
        $this->assertSame(
            $quoteTransfer->getItems()->offsetGet(1)->getMerchantReference(),
            $expandedQuoteTransfer->getItems()->offsetGet(1)->getShipment()->getMerchantReference()
        );
    }
}
