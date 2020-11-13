<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\RestAddressBuilder;
use Generated\Shared\DataBuilder\RestShipmentsBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentsRestApi
 * @group Business
 * @group MapShipmentsToQuoteTest
 * Add your own group annotations below this line
 */
class MapShipmentsToQuoteTest extends Unit
{
    protected const FAKE_ID_SHIPMENT_METHOD = 6666;
    protected const FAKE_GROUP_KEY = 'FAKE_GROUP_KEY';
    protected const FAKE_ADDRESS_1 = 'FAKE_ADDRESS_1';

    protected const DEFAULT_PRICE_LIST = [
        'DE' => [
            'EUR' => [
                'netAmount' => 10,
                'grossAmount' => 15,
            ],
        ],
    ];

    /**
     * @var \SprykerTest\Zed\ShipmentsRestApi\ShipmentsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapShipmentsToQuoteAssignsShipmentTransferToQuoteItems(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->buildQuote();
        $restCheckoutRequestAttributesTransfer = $this->buildRestCheckoutRequestAttributes($quoteTransfer);

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->mapShipmentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getItems()->offsetGet(0)->getShipment());
        $this->assertNotNull($quoteTransfer->getItems()->offsetGet(1)->getShipment());
        $this->assertNotNull($quoteTransfer->getItems()->offsetGet(2)->getShipment());

        $this->assertSame(
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getRequestedDeliveryDate(),
            $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getRequestedDeliveryDate()
        );

        $this->assertSame(
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getShippingAddress()->getAddress1(),
            $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getShippingAddress()->getAddress1()
        );

        $this->assertSame(
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getIdShipmentMethod(),
            $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getMethod()->getIdShipmentMethod()
        );
        $this->assertSame(
            (string)$restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getIdShipmentMethod(),
            $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getShipmentSelection()
        );
    }

    /**
     * @return void
     */
    public function testMapShipmentsToQuoteWithEmptyShipments(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->buildQuote();
        $restCheckoutRequestAttributesTransfer = new RestCheckoutRequestAttributesTransfer();

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->mapShipmentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getItems()->offsetGet(0)->getShipment());
    }

    /**
     * @return void
     */
    public function testMapShipmentsToQuoteWithFakeShipmentMethodId(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->buildQuote();
        $restCheckoutRequestAttributesTransfer = $this->buildRestCheckoutRequestAttributes($quoteTransfer);
        $restCheckoutRequestAttributesTransfer->getShipments()
            ->offsetGet(0)
            ->setIdShipmentMethod(static::FAKE_ID_SHIPMENT_METHOD);

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->mapShipmentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getItems()->offsetGet(0)->getShipment()->getMethod());
        $this->assertNull($quoteTransfer->getItems()->offsetGet(0)->getShipment()->getShipmentSelection());
    }

    /**
     * @return void
     */
    public function testMapShipmentsToQuoteWithFakeItemGroupKey(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->buildQuote();
        $restCheckoutRequestAttributesTransfer = $this->buildRestCheckoutRequestAttributes($quoteTransfer);
        $quoteTransfer->getItems()
            ->offsetGet(0)
            ->setGroupKey(static::FAKE_GROUP_KEY);

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->mapShipmentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getItems()->offsetGet(0)->getShipment());
    }

    /**
     * @return void
     */
    public function testMapShipmentsToQuoteOverridesItemShipment(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->buildQuote();
        $restCheckoutRequestAttributesTransfer = $this->buildRestCheckoutRequestAttributes($quoteTransfer);
        $quoteTransfer->getItems()
            ->offsetGet(0)
            ->setShipment(
                (new ShipmentTransfer())
                    ->setShippingAddress((new AddressTransfer())->setAddress1(static::FAKE_ADDRESS_1))
            );

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->mapShipmentsToQuote($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        // Assert
        $this->assertSame(
            $restCheckoutRequestAttributesTransfer->getShipments()->offsetGet(0)->getShippingAddress()->getAddress1(),
            $quoteTransfer->getItems()->offsetGet(0)->getShipment()->getShippingAddress()->getAddress1()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function buildRestCheckoutRequestAttributes(QuoteTransfer $quoteTransfer): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = new RestCheckoutRequestAttributesTransfer();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $shipmentMethodTransfer = $this->tester->haveShipmentMethod(
                [],
                [],
                static::DEFAULT_PRICE_LIST,
                [$quoteTransfer->getStore()->getIdStore()]
            );

            $restShipmentsTransfer = (new RestShipmentsBuilder())->build()
                ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod())
                ->setShippingAddress((new RestAddressBuilder())->build())
                ->addItem($itemTransfer->getGroupKey());

            $restCheckoutRequestAttributesTransfer->addShipment($restShipmentsTransfer);
        }

        return $restCheckoutRequestAttributesTransfer;
    }
}
