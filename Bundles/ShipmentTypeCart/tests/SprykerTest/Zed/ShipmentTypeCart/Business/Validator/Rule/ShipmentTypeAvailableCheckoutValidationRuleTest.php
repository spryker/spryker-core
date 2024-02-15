<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeCart\Business\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreator;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeAvailableCheckoutValidationRule;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeCart
 * @group Business
 * @group Validator
 * @group Rule
 * @group ShipmentTypeAvailableCheckoutValidationRuleTest
 * Add your own group annotations below this line
 */
class ShipmentTypeAvailableCheckoutValidationRuleTest extends Unit
{
    /**
     * @return void
     */
    public function testIsQuoteReadyForCheckoutReturnTrue(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeReaderMock->method('getShipmentTypeCollection')->willReturn(new ShipmentTypeCollectionTransfer());
        $shipmentTypeAvailableCheckoutValidationRule = $this->createShipmentTypeAvailableCheckoutValidationRule($shipmentTypeReaderMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));

        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $shipmentTypeAvailableCheckoutValidationRule->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($result);
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testIsQuoteReadyForCheckoutReturnFalse(): void
    {
        // Arrange
        $shipmentTypeReaderMock = $this->createShipmentTypeReaderMock();
        $shipmentTypeCollectionTransfer = (new ShipmentTypeCollectionTransfer())
            ->addShipmentType((new ShipmentTypeTransfer())
                ->setIdShipmentType(1)
                ->setUuid('uuid'));
        $shipmentTypeReaderMock->method('getShipmentTypeCollection')->willReturn($shipmentTypeCollectionTransfer);
        $shipmentTypeReaderMock->method('getActiveShipmentTypeCollection')->willReturn($shipmentTypeCollectionTransfer);
        $shipmentTypeAvailableCheckoutValidationRule = $this->createShipmentTypeAvailableCheckoutValidationRule($shipmentTypeReaderMock);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));
        $quoteTransfer->addItem((new ItemTransfer())->setShipment((new ShipmentTransfer())->setMethod(
            (new ShipmentMethodTransfer())->setShipmentType(
                (new ShipmentTypeTransfer())
                    ->setName('Delivery')
                    ->setIdShipmentType(2)
                    ->setUuid('uuid2'),
            ),
        )));
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $result = $shipmentTypeAvailableCheckoutValidationRule->isQuoteReadyForCheckout($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($result);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @param \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface|null $shipmentTypeReaderMock
     *
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface
     */
    protected function createShipmentTypeAvailableCheckoutValidationRule(
        ?ShipmentTypeReaderInterface $shipmentTypeReaderMock = null
    ): ShipmentTypeCheckoutValidationRuleInterface {
        return new ShipmentTypeAvailableCheckoutValidationRule(
            $shipmentTypeReaderMock ?? $this->createShipmentTypeReaderMock(),
            $this->createSalesShipmentTypeValidationErrorCreator(),
        );
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Reader\ShipmentTypeReaderInterface
     */
    protected function createShipmentTypeReaderMock(): ShipmentTypeReaderInterface
    {
        return $this->getMockBuilder(ShipmentTypeReaderInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ErrorCreator\SalesShipmentTypeValidationErrorCreatorInterface
     */
    public function createSalesShipmentTypeValidationErrorCreator(): SalesShipmentTypeValidationErrorCreatorInterface
    {
        return new SalesShipmentTypeValidationErrorCreator();
    }
}
