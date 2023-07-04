<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferShipmentTypeStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferShipmentTypeStorage
 * @group ProductOfferShipmentTypeStorageClientTest
 * Add your own group annotations below this line
 */
class ProductOfferShipmentTypeStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_1 = 'test-product-offer-reference-1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE_2 = 'test-product-offer-reference-2';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_UUID = 'uuid1';

    /**
     * @var int
     */
    protected const SHIPMENT_TYPE_ID = 777;

    /**
     * @var \SprykerTest\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageClientTester
     */
    protected ProductOfferShipmentTypeStorageClientTester $tester;

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesThrowsAnExceptionWhenProductOfferReferenceIsEmpty(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes(new ProductOfferStorageTransfer());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExists(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExistsForProductOfferReference(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_2,
            ['shipmentTypeUuids' => [static::SHIPMENT_TYPE_UUID]],
            static::STORE_NAME_DE,
        );

        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->mockShipmentTypeStorageData($shipmentTypeTransfer, static::STORE_NAME_DE);

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoProductOfferShipmentTypeDataExistsForStore(): void
    {
        // Arrange
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            ['shipmentTypeUuids' => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockShipmentTypeStorageData(
            $shipmentTypeTransfer,
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_AT);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesDoesntExpandWhenNoShipmentTypesFoundByShipmentTypeUuids(): void
    {
        // Arrange
        $shipmentTypeTransfer = (new ShipmentTypeTransfer())
            ->setUuid(static::SHIPMENT_TYPE_UUID)
            ->setIdShipmentType(static::SHIPMENT_TYPE_ID);

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            ['shipmentTypeUuids' => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertEmpty($productOfferStorageTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageWithShipmentTypesExpandsWhenProductOfferShipmentTypeDataExistsForProductOfferReferenceAndStore(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE_1);
        $shipmentTypeTransfer = $this->tester->haveShipmentType();
        $this->tester->mockProductOfferShipmentTypeStorageData(
            static::TEST_PRODUCT_OFFER_REFERENCE_1,
            ['shipmentTypeUuids' => [$shipmentTypeTransfer->getUuidOrFail()]],
            static::STORE_NAME_DE,
        );

        $this->tester->mockShipmentTypeStorageData(
            $shipmentTypeTransfer,
            static::STORE_NAME_DE,
        );

        $this->tester->mockStoreClient(static::STORE_NAME_DE);

        // Act
        $productOfferStorageTransfer = $this->tester->getClient()->expandProductOfferStorageWithShipmentTypes($productOfferStorageTransfer);

        // Assert
        $this->assertCount(1, $productOfferStorageTransfer->getShipmentTypes());
        $this->assertSame($shipmentTypeTransfer->getUuidOrFail(), $productOfferStorageTransfer->getShipmentTypes()->offsetGet(0)->getUuidOrFail());
    }
}
