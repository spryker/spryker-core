<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantShipment\Communication;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantShipment
 * @group Communication
 * @group ExpandCartChangeShipmentWithMerchantReferenceTest
 * Add your own group annotations below this line
 */
class ExpandCartChangeShipmentWithMerchantReferenceTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantShipment\MerchantShipmentCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandCartChangeShipmentWithMerchantReferenceExpandsItemShipmentWithMerchantReference(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()))
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()));

        // Act
        $expandedCartChangeTransfer = $this->tester->getShipmentExpander()
            ->expandCartChangeShipmentWithMerchantReference($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $cartChangeTransfer->getItems()->offsetGet(0)->getMerchantReference(),
            $expandedCartChangeTransfer->getItems()->offsetGet(0)->getShipment()->getMerchantReference()
        );
        $this->assertSame(
            $cartChangeTransfer->getItems()->offsetGet(1)->getMerchantReference(),
            $expandedCartChangeTransfer->getItems()->offsetGet(1)->getShipment()->getMerchantReference()
        );
    }

    /**
     * @return void
     */
    public function testExpandCartChangeShipmentWithMerchantReferenceExpandsItemShipmentWithoutShipment(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem((new ItemBuilder())->build())
            ->addItem((new ItemBuilder())->build()->setShipment(new ShipmentTransfer()));

        // Act
        $expandedCartChangeTransfer = $this->tester->getShipmentExpander()
            ->expandCartChangeShipmentWithMerchantReference($cartChangeTransfer);

        // Assert
        $this->assertNull($expandedCartChangeTransfer->getItems()->offsetGet(0)->getShipment());
        $this->assertSame(
            $cartChangeTransfer->getItems()->offsetGet(1)->getMerchantReference(),
            $expandedCartChangeTransfer->getItems()->offsetGet(1)->getShipment()->getMerchantReference()
        );
    }
}
