<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferServicePointStorage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServiceStorageTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferServicePointStorage
 * @group ProductOfferServicePointStorageClientTest
 * Add your own group annotations below this line
 */
class ProductOfferServicePointStorageClientTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'de';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Reader\ServicePointStorageReader::KEY_ID
     *
     * @var string
     */
    protected const SERVICE_POINT_STORAGE_KEY_ID = 'id';

    /**
     * @uses \Spryker\Client\ServicePointStorage\Generator\ServicePointStorageKeyGenerator::MAPPING_TYPE_UUID
     *
     * @var string
     */
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @uses \Spryker\Shared\ServicePointStorage\ServicePointStorageConfig::SERVICE_POINT_RESOURCE_NAME
     *
     * @var string
     */
    protected const SERVICE_POINT_RESOURCE_NAME = 'service_point';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_ONE = 'product-offer-reference-one';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_TWO = 'product-offer-reference-two';

    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID = 'service-point-uuid';

    /**
     * @var int
     */
    protected const SERVICE_POINT_ID = 1000000;

    /**
     * @var string
     */
    protected const SERVICE_UUID_1 = 'service-uuid-1';

    /**
     * @var string
     */
    protected const SERVICE_UUID_2 = 'service-uuid-2';

    /**
     * @var \SprykerTest\Client\ProductOfferServicePointStorage\ProductOfferServicePointStorageClientTester
     */
    protected ProductOfferServicePointStorageClientTester $tester;

    /**
     * @return void
     */
    public function testExpandProductOfferStorageCollectionWithServicesShouldReturnCollectionWithServices(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer(
                (new ProductOfferStorageTransfer())->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_ONE),
            );

        $productOfferServiceStorageKey = sprintf(
            '%s:%s:%s',
            ProductOfferServicePointStorageConfig::PRODUCT_OFFER_SERVICE_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::PRODUCT_OFFER_REFERENCE_ONE,
        );

        $this->tester->setToStorage(
            $productOfferServiceStorageKey,
            (new ProductOfferServiceStorageTransfer())
                ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_ONE)
                ->setServicePointUuid(static::SERVICE_POINT_UUID)
                ->addServiceUuid(static::SERVICE_UUID_1)
                ->addServiceUuid(static::SERVICE_UUID_2)
                ->toArray(),
        );

        $servicePointStorageUuidKey = sprintf(
            '%s:%s:%s:%s',
            static::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::MAPPING_TYPE_UUID,
            static::SERVICE_POINT_UUID,
        );

        $this->tester->setToStorage(
            $servicePointStorageUuidKey,
            [
                static::SERVICE_POINT_STORAGE_KEY_ID => static::SERVICE_POINT_ID,
            ],
        );

        $servicePointStorageIdKey = sprintf(
            '%s:%s:%s',
            static::SERVICE_POINT_RESOURCE_NAME,
            static::STORE_NAME_DE,
            static::SERVICE_POINT_ID,
        );

        $this->tester->setToStorage(
            $servicePointStorageIdKey,
            (new ServicePointStorageTransfer())
                ->setUuid(static::SERVICE_POINT_UUID)
                ->addService(
                    (new ServiceStorageTransfer())->setUuid(static::SERVICE_UUID_1),
                )
                ->addService(
                    (new ServiceStorageTransfer())->setUuid(static::SERVICE_UUID_2),
                )
                ->toArray(),
        );

        // Act
        $productOfferStorageCollectionTransfer = $this->tester->getClient()->expandProductOfferStorageCollectionWithServices($productOfferStorageCollectionTransfer);

        // Assert
        $this->assertCount(1, $productOfferStorageCollectionTransfer->getProductOffers());
        /** @var \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer */
        $productOfferStorageTransfer = $productOfferStorageCollectionTransfer->getProductOffers()->getIterator()->current();
        $this->assertCount(2, $productOfferStorageTransfer->getServices());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageCollectionWithServicesShouldReturnCollectionWithoutServicesWhenProductOfferHasNotServices(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer(
                (new ProductOfferStorageTransfer())->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_TWO),
            );

        // Act
        $productOfferStorageCollectionTransfer = $this->tester->getClient()->expandProductOfferStorageCollectionWithServices($productOfferStorageCollectionTransfer);

        // Assert
        $this->assertCount(1, $productOfferStorageCollectionTransfer->getProductOffers());
        /** @var \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer */
        $productOfferStorageTransfer = $productOfferStorageCollectionTransfer->getProductOffers()->getIterator()->current();
        $this->assertCount(0, $productOfferStorageTransfer->getServices());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferStorageCollectionWithServicesShouldThrowAnExceptionWhenProductOfferReferenceIsMissing(): void
    {
        // Arrange
        $productOfferStorageCollectionTransfer = (new ProductOfferStorageCollectionTransfer())
            ->addProductOffer(new ProductOfferStorageTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->expandProductOfferStorageCollectionWithServices($productOfferStorageCollectionTransfer);
    }
}
