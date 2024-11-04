<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferServicePointAvailabilityStorage\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServiceStorageTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientBridge;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientBridge;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\ProductOfferServicePointAvailabilityStorageDependencyProvider;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Client\ProductOfferServicePointAvailabilityStorage\ProductOfferServicePointAvailabilityStorageClientTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductOfferServicePointAvailabilityStorage
 * @group Client
 * @group GetProductOfferServicePointAvailabilityCollectionTest
 * Add your own group annotations below this line
 */
class GetProductOfferServicePointAvailabilityCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID_1 = 'SERVICE_POINT_UUID_1';

    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID_2 = 'SERVICE_POINT_UUID_2';

    /**
     * @var string
     */
    protected const SERVICE_TYPE_UUID_1 = 'SERVICE_TYPE_UUID_1';

    /**
     * @var string
     */
    protected const SERVICE_TYPE_UUID_2 = 'SERVICE_TYPE_UUID_2';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_SKU_1 = 'PRODUCT_CONCRETE_SKU_1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_1 = 'PRODUCT_OFFER_REFERENCE_1';

    /**
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_2 = 'PRODUCT_OFFER_REFERENCE_2';

    /**
     * @var \SprykerTest\Client\ProductOfferServicePointAvailabilityStorage\ProductOfferServicePointAvailabilityStorageClientTester
     */
    protected ProductOfferServicePointAvailabilityStorageClientTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnOneAvailabilityWhenProductConcreteSkuProvidedWithAvailabilityAndSingleServicePointRelationship(): void
    {
        // Arrange
        $serviceTypeStorageTransfer = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_1);
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_1);
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->addService($serviceStorageTransfer);
        $productOfferAvailabilityStorageTransfer = (new ProductOfferAvailabilityStorageTransfer())
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail())
            ->setIsNeverOutOfStock(true)
            ->setAvailability(new Decimal(1));

        $this->mockProductOfferStorageClient(
            1,
            (new ProductOfferStorageCollectionTransfer())->addProductOffer($productOfferStorageTransfer),
        );

        $this->mockProductOfferAvailabilityStorageClient(
            1,
            [$productOfferAvailabilityStorageTransfer],
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setServicePointUuids([$servicePointStorageTransfer->getUuidOrFail()])
                    ->setServiceTypeUuid($serviceTypeStorageTransfer->getUuidOrFail())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->addProductOfferServicePointAvailabilityRequestItem(
                        (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                            ->setProductConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail()),
                    ),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityResponseItemTransfers);
        /** @var \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer */
        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers->getIterator()->current();

        $this->assertProductOfferServicePointAvailabilityResponseItem(
            $productOfferStorageTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfer,
            $productOfferAvailabilityStorageTransfer,
            $serviceStorageTransfer,
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnMultipleAvailabilityWhenProductConcreteSkuProvidedWithAvailabilityAndMultipleServicePointRelationship(): void
    {
        // Arrange
        $serviceTypeStorageTransfer = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_1);
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_1);
        $servicePointStorageTransfer2 = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_2);
        $servicePointUuids = [
            $servicePointStorageTransfer->getUuidOrFail() => true,
            $servicePointStorageTransfer2->getUuidOrFail() => true,
        ];
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer);
        $serviceStorageTransfer2 = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer2);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->addService($serviceStorageTransfer)
            ->addService($serviceStorageTransfer2);
        $productOfferAvailabilityStorageTransfer = (new ProductOfferAvailabilityStorageTransfer())
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail())
            ->setIsNeverOutOfStock(true)
            ->setAvailability(new Decimal(1));

        $this->mockProductOfferStorageClient(
            1,
            (new ProductOfferStorageCollectionTransfer())->addProductOffer($productOfferStorageTransfer),
        );

        $this->mockProductOfferAvailabilityStorageClient(
            1,
            [$productOfferAvailabilityStorageTransfer],
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setServicePointUuids([
                        $servicePointStorageTransfer->getUuidOrFail(),
                        $servicePointStorageTransfer2->getUuidOrFail(),
                    ])
                    ->setServiceTypeUuid($serviceTypeStorageTransfer->getUuid())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->addProductOfferServicePointAvailabilityRequestItem(
                        (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                            ->setProductConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail()),
                    ),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        // Assert
        $this->assertCount(2, $productOfferServicePointAvailabilityResponseItemTransfers);

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            $this->assertProductOfferServicePointAvailabilityResponseItem(
                $productOfferStorageTransfer,
                $productOfferServicePointAvailabilityResponseItemTransfer,
                $productOfferAvailabilityStorageTransfer,
                null,
            );

            $this->assertTrue(isset($servicePointUuids[$productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuidOrFail()]));
            unset($servicePointUuids[$productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuidOrFail()]);
        }

        $this->assertCount(0, $servicePointUuids);
    }

    /**
     * @return void
     */
    public function testShouldNotReturnAvailabilityWhenProductConcreteSkuProvidedWithoutAvailability(): void
    {
        // Arrange
        $serviceTypeStorageTransfer = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_1);
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_1);
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->addService($serviceStorageTransfer);

        $this->mockProductOfferStorageClient(
            1,
            (new ProductOfferStorageCollectionTransfer())->addProductOffer($productOfferStorageTransfer),
        );

        $this->mockProductOfferAvailabilityStorageClient(
            1,
            [],
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setServicePointUuids([$servicePointStorageTransfer->getUuidOrFail()])
                    ->setServiceTypeUuid($serviceTypeStorageTransfer->getUuidOrFail())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->addProductOfferServicePointAvailabilityRequestItem(
                        (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                            ->setProductConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail()),
                    ),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        // Assert
        $this->assertCount(0, $productOfferServicePointAvailabilityResponseItemTransfers);
    }

    /**
     * @return void
     */
    public function testShouldReturnAvailabilityWhenProductConcreteSkuProvidedWithAvailabilityAndMultipleServicePointRelationshipFilteredByServicePointUuid(): void
    {
        // Arrange
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_1);
        $servicePointStorageTransfer2 = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_2);
        $serviceTypeStorageTransfer = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_1);
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer);
        $serviceStorageTransfer2 = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer2);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->addService($serviceStorageTransfer)
            ->addService($serviceStorageTransfer2);
        $productOfferAvailabilityStorageTransfer = (new ProductOfferAvailabilityStorageTransfer())
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail())
            ->setIsNeverOutOfStock(true)
            ->setAvailability(new Decimal(1));

        $this->mockProductOfferStorageClient(
            1,
            (new ProductOfferStorageCollectionTransfer())->addProductOffer($productOfferStorageTransfer),
        );

        $this->mockProductOfferAvailabilityStorageClient(
            1,
            [$productOfferAvailabilityStorageTransfer],
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setServicePointUuids([$servicePointStorageTransfer2->getUuidOrFail()])
                    ->setServiceTypeUuid($serviceTypeStorageTransfer->getUuidOrFail())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->addProductOfferServicePointAvailabilityRequestItem(
                        (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                            ->setProductConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail()),
                    ),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityResponseItemTransfers);

        /** @var \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer */
        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers->getIterator()->current();

        $this->assertProductOfferServicePointAvailabilityResponseItem(
            $productOfferStorageTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfer,
            $productOfferAvailabilityStorageTransfer,
            $serviceStorageTransfer2,
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnAvailabilityWhenProductConcreteSkuProvidedWithAvailabilityAndMultipleServicePointRelationshipFilteredByServiceTypeUuid(): void
    {
        // Arrange
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_1);
        $servicePointStorageTransfer2 = (new ServicePointStorageTransfer())
            ->setUuid(static::SERVICE_POINT_UUID_2);
        $serviceTypeStorageTransfer = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_1);
        $serviceTypeStorageTransfer2 = (new ServiceTypeStorageTransfer())
            ->setUuid(static::SERVICE_TYPE_UUID_2);
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer)
            ->setServicePoint($servicePointStorageTransfer);
        $serviceStorageTransfer2 = (new ServiceStorageTransfer())
            ->setServiceType($serviceTypeStorageTransfer2)
            ->setServicePoint($servicePointStorageTransfer2);
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1)
            ->setProductOfferReference(static::PRODUCT_OFFER_REFERENCE_1)
            ->addService($serviceStorageTransfer)
            ->addService($serviceStorageTransfer2);
        $productOfferAvailabilityStorageTransfer = (new ProductOfferAvailabilityStorageTransfer())
            ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail())
            ->setIsNeverOutOfStock(true)
            ->setAvailability(new Decimal(1));

        $this->mockProductOfferStorageClient(
            1,
            (new ProductOfferStorageCollectionTransfer())->addProductOffer($productOfferStorageTransfer),
        );

        $this->mockProductOfferAvailabilityStorageClient(
            1,
            [$productOfferAvailabilityStorageTransfer],
        );

        $productOfferServicePointAvailabilityCriteriaTransfer = (new ProductOfferServicePointAvailabilityCriteriaTransfer())
            ->setProductOfferServicePointAvailabilityConditions(
                (new ProductOfferServicePointAvailabilityConditionsTransfer())
                    ->setServicePointUuids([
                        $servicePointStorageTransfer->getUuidOrFail(),
                        $servicePointStorageTransfer2->getUuidOrFail(),
                    ])
                    ->setServiceTypeUuid($serviceTypeStorageTransfer2->getUuidOrFail())
                    ->setStoreName(static::STORE_NAME_DE)
                    ->addProductOfferServicePointAvailabilityRequestItem(
                        (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                            ->setProductConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail()),
                    ),
            );

        // Act
        $productOfferServicePointAvailabilityCollectionTransfer = $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
        $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems();

        // Assert
        $this->assertCount(1, $productOfferServicePointAvailabilityResponseItemTransfers);

        /** @var \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer */
        $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfers->getIterator()->current();

        $this->assertProductOfferServicePointAvailabilityResponseItem(
            $productOfferStorageTransfer,
            $productOfferServicePointAvailabilityResponseItemTransfer,
            $productOfferAvailabilityStorageTransfer,
            $serviceStorageTransfer2,
        );
    }

    /**
     * @dataProvider getGetProductOfferServicePointAvailabilityCollectionWithInvalidRequestDataProvider
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     * @param string $missingPropertyName
     *
     * @return void
     */
    public function testWithInvalidRequest(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer,
        string $missingPropertyName
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessageMatches('/' . $missingPropertyName . '/');

        // Act
        $this->tester->getClient()->getProductOfferServicePointAvailabilityCollection($productOfferServicePointAvailabilityCriteriaTransfer);
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function getGetProductOfferServicePointAvailabilityCollectionWithInvalidRequestDataProvider(): array
    {
        return [
            'Check validation for ProductOfferServicePointAvailabilityConditions' => [
                new ProductOfferServicePointAvailabilityCriteriaTransfer(),
                ProductOfferServicePointAvailabilityCriteriaTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CONDITIONS,
            ],
            'Check validation for ProductOfferServicePointAvailabilityRequestItems' => [
                (new ProductOfferServicePointAvailabilityCriteriaTransfer())
                    ->setProductOfferServicePointAvailabilityConditions(
                        (new ProductOfferServicePointAvailabilityConditionsTransfer())
                            ->setServicePointUuids([static::SERVICE_POINT_UUID_1])
                            ->setServiceTypeUuid(static::SERVICE_TYPE_UUID_1)
                            ->setStoreName(static::STORE_NAME_DE),
                    ),
                ProductOfferServicePointAvailabilityConditionsTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS,
            ],
            'Check validation for ProductOfferServicePointAvailabilityRequestItems sku' => [
                (new ProductOfferServicePointAvailabilityCriteriaTransfer())
                    ->setProductOfferServicePointAvailabilityConditions(
                        (new ProductOfferServicePointAvailabilityConditionsTransfer())
                            ->setServicePointUuids([static::SERVICE_POINT_UUID_1])
                            ->setServiceTypeUuid(static::SERVICE_TYPE_UUID_1)
                            ->setStoreName(static::STORE_NAME_DE)
                            ->addProductOfferServicePointAvailabilityRequestItem(new ProductOfferServicePointAvailabilityRequestItemTransfer()),
                    ),
                ProductOfferServicePointAvailabilityRequestItemTransfer::PRODUCT_CONCRETE_SKU,
            ],
            'Check validation for ProductOfferServicePointAvailabilityConditions service point uuids' => [
                (new ProductOfferServicePointAvailabilityCriteriaTransfer())
                    ->setProductOfferServicePointAvailabilityConditions(
                        (new ProductOfferServicePointAvailabilityConditionsTransfer())
                            ->setServiceTypeUuid(static::SERVICE_TYPE_UUID_1)
                            ->setStoreName(static::STORE_NAME_DE)
                            ->addProductOfferServicePointAvailabilityRequestItem(
                                (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                                    ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1),
                            ),
                    ),
                ProductOfferServicePointAvailabilityConditionsTransfer::SERVICE_POINT_UUIDS,
            ],
            'Check validation for ProductOfferServicePointAvailabilityConditions service type uuid' => [
                (new ProductOfferServicePointAvailabilityCriteriaTransfer())
                    ->setProductOfferServicePointAvailabilityConditions(
                        (new ProductOfferServicePointAvailabilityConditionsTransfer())
                            ->setServicePointUuids([static::SERVICE_POINT_UUID_1])
                            ->setStoreName(static::STORE_NAME_DE)
                            ->addProductOfferServicePointAvailabilityRequestItem(
                                (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                                    ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1),
                            ),
                    ),
                ProductOfferServicePointAvailabilityConditionsTransfer::SERVICE_TYPE_UUID,
            ],
            'Check validation for ProductOfferServicePointAvailabilityConditions store name' => [
                (new ProductOfferServicePointAvailabilityCriteriaTransfer())
                    ->setProductOfferServicePointAvailabilityConditions(
                        (new ProductOfferServicePointAvailabilityConditionsTransfer())
                            ->setServicePointUuids([static::SERVICE_POINT_UUID_1])
                            ->setServiceTypeUuid(static::SERVICE_TYPE_UUID_1)
                            ->addProductOfferServicePointAvailabilityRequestItem(
                                (new ProductOfferServicePointAvailabilityRequestItemTransfer())
                                    ->setProductConcreteSku(static::PRODUCT_CONCRETE_SKU_1),
                            ),
                    ),
                ProductOfferServicePointAvailabilityConditionsTransfer::STORE_NAME,
            ],
        ];
    }

    /**
     * @param int $getProductOfferStoragesBySkusMethodCallCount
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return void
     */
    protected function mockProductOfferStorageClient(
        int $getProductOfferStoragesBySkusMethodCallCount,
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): void {
        $productOfferStorageClientMock = $this->createMock(ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientBridge::class);

        $productOfferStorageClientMock
            ->expects($this->exactly($getProductOfferStoragesBySkusMethodCallCount))
            ->method('getProductOfferStoragesBySkus')
            ->willReturn($productOfferStorageCollectionTransfer);

        $this->tester->setDependency(ProductOfferServicePointAvailabilityStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE, $productOfferStorageClientMock);
    }

    /**
     * @param int $getByProductOfferReferencesMethodCallCount
     * @param list<\Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer> $productOfferAvailabilityStorageTransfers
     *
     * @return void
     */
    protected function mockProductOfferAvailabilityStorageClient(
        int $getByProductOfferReferencesMethodCallCount,
        array $productOfferAvailabilityStorageTransfers
    ): void {
        $productOfferAvailabilityStorageClientMock = $this->createMock(ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientBridge::class);

        $productOfferAvailabilityStorageClientMock
            ->expects($this->exactly($getByProductOfferReferencesMethodCallCount))
            ->method('getByProductOfferReferences')
            ->willReturn($productOfferAvailabilityStorageTransfers);

        $this->tester->setDependency(ProductOfferServicePointAvailabilityStorageDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE, $productOfferAvailabilityStorageClientMock);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     * @param \Generated\Shared\Transfer\ServiceStorageTransfer|null $serviceStorageTransfer2
     *
     * @return void
     */
    protected function assertProductOfferServicePointAvailabilityResponseItem(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer,
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer,
        ?ServiceStorageTransfer $serviceStorageTransfer2
    ): void {
        $this->assertSame(
            $productOfferStorageTransfer->getProductOfferReferenceOrFail(),
            $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
        );
        $this->assertSame(
            $productOfferStorageTransfer->getProductConcreteSkuOrFail(),
            $productOfferServicePointAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
        );
        $this->assertSame(
            $productOfferAvailabilityStorageTransfer->getAvailabilityOrFail()->toInt(),
            $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantityOrFail(),
        );
        $this->assertSame(
            $productOfferAvailabilityStorageTransfer->getIsNeverOutOfStockOrFail(),
            $productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStockOrFail(),
        );

        if ($serviceStorageTransfer2) {
            $this->assertSame(
                $serviceStorageTransfer2->getServicePoint()->getUuidOrFail(),
                $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuidOrFail(),
            );
        }
    }
}
