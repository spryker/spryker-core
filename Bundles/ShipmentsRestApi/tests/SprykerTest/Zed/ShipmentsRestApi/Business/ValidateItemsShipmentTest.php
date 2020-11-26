<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentsRestApi
 * @group Business
 * @group ValidateItemsShipmentTest
 * Add your own group annotations below this line
 */
class ValidateItemsShipmentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ShipmentsRestApi\Business\Validator\CartItemCheckoutDataValidator::GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED
     */
    protected const GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED = 'checkout.validation.item.no_shipment_selected';

    /**
     * @var \SprykerTest\Zed\ShipmentsRestApi\ShipmentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillNotReturnErrorIfNoShipmentDataIsProvided(): void
    {
        // Arrange
        $checkoutDataTransfer = (new CheckoutDataBuilder([CheckoutDataTransfer::SHIPMENTS => []]))->build();

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillNotReturnErrorIfValidShipmentDataPerItemIsProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->withItem()->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withQuote($quoteTransfer->toArray())
            ->build()
            ->addShipment(
                (new RestShipmentsTransfer())->setItems([$quoteTransfer->getItems()->offsetGet(0)->getGroupKey()])
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillNotReturnErrorIfValidShipmentDataPerBundleItemIsProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->withBundleItem()->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withQuote($quoteTransfer->toArray())
            ->build()
            ->addShipment(
                (new RestShipmentsTransfer())->setItems([$quoteTransfer->getBundleItems()->offsetGet(0)->getGroupKey()])
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillReturnErrorIfInvalidShipmentDataPerItemIsProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::GROUP_KEY => 'group-key-1'])
            ->withBundleItem([ItemTransfer::GROUP_KEY => 'group-key-2'])
            ->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withQuote($quoteTransfer->toArray())
            ->build()
            ->addShipment(
                (new RestShipmentsTransfer())->setItems(['group-key-2'])
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED,
            $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillReturnErrorIfInvalidShipmentDataPerItemBundleIsProvided(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::GROUP_KEY => 'group-key-1'])
            ->withBundleItem([ItemTransfer::GROUP_KEY => 'group-key-2'])
            ->build();
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withQuote($quoteTransfer->toArray())
            ->build()
            ->addShipment(
                (new RestShipmentsTransfer())->setItems(['group-key-1'])
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::GLOSSARY_KEY_ITEM_NO_SHIPMENT_SELECTED,
            $checkoutResponseTransfer->getErrors()->offsetGet(0)->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testValidateItemsShipmentWillThrowExceptionIfQuoteIsNotProvided(): void
    {
        // Arrange
        $this->expectException(RequiredTransferPropertyException::class);
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->build()
            ->addShipment(new RestShipmentsTransfer());

        $checkoutDataTransfer->setQuote(null);

        // Act
        $this->tester->getFacade()->validateItemsShipment($checkoutDataTransfer);
    }
}
