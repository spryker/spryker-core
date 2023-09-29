<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypesRestApi\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;
use Spryker\Zed\ShipmentType\Communication\Plugin\Shipment\ShipmentTypeShipmentMethodCollectionExpanderPlugin;
use SprykerTest\Zed\ShipmentTypesRestApi\ShipmentTypesRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypesRestApi
 * @group Business
 * @group Facade
 * @group ValidateShipmentTypeCheckoutDataTest
 * Add your own group annotations below this line
 */
class ValidateShipmentTypeCheckoutDataTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ShipmentTypesRestApi\Business\Validator\MultiShipmentShipmentTypeCheckoutDataValidator::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_IS_NOT_AVAILABLE
     *
     * @var string
     */
    protected const EXPECTED_ERROR_MESSAGE = 'shipment_types_rest_api.error.shipment_type_not_available';

    /**
     * @var \SprykerTest\Zed\ShipmentTypesRestApi\ShipmentTypesRestApiBusinessTester
     */
    protected ShipmentTypesRestApiBusinessTester $tester;

    /**
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->setDependency(
            ShipmentDependencyProvider::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER,
            [
                new ShipmentTypeShipmentMethodCollectionExpanderPlugin(),
            ],
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenShipmentTypeIsInactiveAndMultiShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->addShipment(
                (new RestShipmentsTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::EXPECTED_ERROR_MESSAGE,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenShipmentTypeHasNoStoreRelationAndMultiShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->addShipment(
                (new RestShipmentsTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::EXPECTED_ERROR_MESSAGE,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentMethodHasActiveShipmentTypeWithRelatedStoreAndMultiShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->addShipment(
                (new RestShipmentsTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentMethodHasNoShipmentTypeAndMultiShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->addShipment(
                (new RestShipmentsTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentHaveNoAssignedMethodsAndMultiShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->addShipment(
                (new RestShipmentsTransfer()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenShipmentTypeIsInactiveAndSingleShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => false,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->setShipment(
                (new RestShipmentTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::EXPECTED_ERROR_MESSAGE,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenShipmentTypeHasNoStoreRelationAndSingleShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->setShipment(
                (new RestShipmentTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
        $this->assertEquals(
            static::EXPECTED_ERROR_MESSAGE,
            $checkoutResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentMethodHasActiveShipmentTypeWithRelatedStoreAndSingleShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();
        $shipmentTypeTransfer = $this->tester->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $this->tester->createShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->setShipment(
                (new RestShipmentTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentMethodHasNoShipmentTypeAndSingleShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod();

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->setShipment(
                (new RestShipmentTransfer())
                    ->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorsWhenShipmentHaveNoAssignedMethodsAndSingleShipmentIsUsed(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $checkoutDataTransfer = (new CheckoutDataTransfer())
            ->setQuote(
                (new QuoteTransfer())
                    ->setStore($storeTransfer),
            )
            ->setShipment(
                (new RestShipmentTransfer()),
            );

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateShipmentTypeCheckoutData($checkoutDataTransfer);

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }
}
