<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePointStorage;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferServiceStorageTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery;
use Orm\Zed\ProductOfferServicePointStorage\Persistence\Base\SpyProductOfferServiceStorage;
use Orm\Zed\ProductOfferServicePointStorage\Persistence\SpyProductOfferServiceStorageQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\ProductOfferServicePointStorage\Business\ProductOfferServicePointStorageFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ProductOfferServicePointStorage\PHPMD)
 */
class ProductOfferServicePointStorageBusinessTester extends Actor
{
    use _generated\ProductOfferServicePointStorageBusinessTesterActions;

    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    public const KEY_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE = 'PRODUCT_OFFER_REFERENCE';

    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE_2 = 'PRODUCT_OFFER_REFERENCE_2';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const PRODUCT_OFFER_APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var string
     */
    protected const KEY_SERVICE_POINT_UUID = 'service_point_uuid';

    /**
     * @var string
     */
    protected const KEY_SERVICE_UUIDS = 'service_uuids';

    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID = 'SERVICE_POINT_UUID';

    /**
     * @var string
     */
    protected const SERVICE_UUID = 'SERVICE_UUID';

    /**
     * @var string
     */
    protected const SERVICE_UUID_2 = 'SERVICE_UUID_2';

    /**
     * @return array<string, array>
     */
    public static function getProductOfferServiceDataProvider(): array
    {
        return [
            'Should publish data when product offer and service are active' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [
                    [ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true],
                    [ServiceTransfer::UUID => static::SERVICE_UUID_2, ServiceTransfer::IS_ACTIVE => true],
                ],
                [],
                [],
                static::PRODUCT_OFFER_REFERENCE,
                1,
                static::STORE_NAME_DE,
                [
                    static::KEY_PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    static::KEY_SERVICE_POINT_UUID => static::SERVICE_POINT_UUID,
                    static::KEY_SERVICE_UUIDS => [static::SERVICE_UUID, static::SERVICE_UUID_2],
                ],
            ],
            'Should un-publish data when product offer is not active' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => false,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                0,
                '',
                [],
            ],
            'Should un-publish data when product offer concrete product is not active' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => false],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                0,
                '',
                [],
            ],
            'Should un-publish data when product offer is not approved' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_DENIED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                0,
                '',
                [],
            ],
            'Should un-publish data when service is not active' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [
                    [ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true],
                    [ServiceTransfer::UUID => static::SERVICE_UUID_2, ServiceTransfer::IS_ACTIVE => false],
                ],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID, static::SERVICE_UUID_2],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                1,
                static::STORE_NAME_DE,
                [
                    static::KEY_PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    static::KEY_SERVICE_POINT_UUID => static::SERVICE_POINT_UUID,
                    static::KEY_SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
            ],
            'Should un-publish data when service point is not active' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => false],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                0,
                '',
                [],
            ],
            'Should un-publish data when product offer and service point are not available in the same store' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_AT],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_DE],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                0,
                '',
                [],
            ],
            'Should un-publish old data and publish new data when store relation is changed' => [
                [static::STORE_NAME_DE],
                [ProductConcreteTransfer::IS_ACTIVE => true],
                [
                    ProductOfferTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferTransfer::IS_ACTIVE => true,
                    ProductOfferTransfer::APPROVAL_STATUS => static::PRODUCT_OFFER_APPROVAL_STATUS_APPROVED,
                ],
                [static::STORE_NAME_DE],
                [ServicePointTransfer::UUID => static::SERVICE_POINT_UUID, ServicePointTransfer::IS_ACTIVE => true],
                [[ServiceTransfer::UUID => static::SERVICE_UUID, ServiceTransfer::IS_ACTIVE => true]],
                [static::STORE_NAME_AT],
                [
                    ProductOfferServiceStorageTransfer::PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    ProductOfferServiceStorageTransfer::SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
                static::PRODUCT_OFFER_REFERENCE,
                1,
                static::STORE_NAME_DE,
                [
                    static::KEY_PRODUCT_OFFER_REFERENCE => static::PRODUCT_OFFER_REFERENCE,
                    static::KEY_SERVICE_POINT_UUID => static::SERVICE_POINT_UUID,
                    static::KEY_SERVICE_UUIDS => [static::SERVICE_UUID],
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function ensureProductOfferServiceStorageTableAndRelationsAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferServiceStorageQuery());
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferServiceQuery());
        $this->ensureDatabaseTableIsEmpty($this->getProductOfferQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServiceQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServicePointQuery());
        $this->ensureDatabaseTableIsEmpty($this->getServiceTypeQuery());
    }

    /**
     * @return void
     */
    public function setDependencies(): void
    {
        $this->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $this->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer
     * @param list<string> $storeNames
     *
     * @return void
     */
    public function createProductOfferServiceStorageByStoreRelations(
        ProductOfferServiceStorageTransfer $productOfferServiceStorageTransfer,
        array $storeNames
    ): void {
        foreach ($storeNames as $storeName) {
            $productOfferServiceStorageEntity = $this->getProductOfferServiceStorageQuery()
                ->filterByProductOfferReference($productOfferServiceStorageTransfer->getProductOfferReferenceOrFail())
                ->filterByStore($storeName)
                ->findOneOrCreate();

            $productOfferServiceStorageEntity->setData($productOfferServiceStorageTransfer->toArray());
            $productOfferServiceStorageEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return int|null
     */
    public function findIdProductOfferService(ProductOfferServiceTransfer $productOfferServiceTransfer): ?int
    {
        $productOfferServiceEntity = $this->getProductOfferServiceQuery()
            ->filterByFkProductOffer($productOfferServiceTransfer->getIdProductOfferOrFail())
            ->filterByFkService($productOfferServiceTransfer->getIdServiceOrFail())
            ->findOne();

        if (!$productOfferServiceEntity) {
            return null;
        }

        return $productOfferServiceEntity->getIdProductOfferService();
    }

    /**
     * @param string $productOfferReference
     *
     * @return list<\Orm\Zed\ProductOfferServicePointStorage\Persistence\Base\SpyProductOfferServiceStorage>
     */
    public function getProductOfferServiceStorageEntitiesByProductOfferReference(string $productOfferReference): array
    {
        return $this->getProductOfferServiceStorageQuery()
            ->filterByProductOfferReference($productOfferReference)
            ->find()
            ->getData();
    }

    /**
     * @param \Orm\Zed\ProductOfferServicePointStorage\Persistence\Base\SpyProductOfferServiceStorage $productOfferServiceStorageEntity
     * @param string $expectedStore
     * @param array<string, mixed> $expectedData
     *
     * @return void
     */
    public function assertProductOfferServiceStorageData(
        SpyProductOfferServiceStorage $productOfferServiceStorageEntity,
        string $expectedStore,
        array $expectedData
    ): void {
        $this->assertSame($expectedStore, $productOfferServiceStorageEntity->getStore());
        $this->assertSame($expectedData, $productOfferServiceStorageEntity->getData());
    }

    /**
     * @param list<string> $productOfferStoreNames
     * @param array<string, mixed> $productData
     * @param array<string, mixed> $productOfferData
     * @param list<string> $servicePointStoreNames
     * @param array<string, mixed> $servicePointData
     * @param array<string, mixed> $servicesData
     * @param list<string> $productOfferServiceDataStoreNames
     * @param array<string, mixed> $productOfferServiceData
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer|\Generated\Shared\Transfer\ServicePointTransfer|list<\Generated\Shared\Transfer\ServiceTransfer>|list<\Generated\Shared\Transfer\ProductOfferServiceTransfer>>
     */
    public function createDataForProductServicePointPublishing(
        array $productOfferStoreNames,
        array $productData,
        array $productOfferData,
        array $servicePointStoreNames,
        array $servicePointData,
        array $servicesData,
        array $productOfferServiceDataStoreNames,
        array $productOfferServiceData
    ): array {
        $productOfferStoreTransfers = [];
        foreach ($productOfferStoreNames as $productOfferStoreName) {
            $productOfferStoreTransfers[] = $this->haveStore([StoreTransfer::NAME => $productOfferStoreName]);
        }

        $productTransfer = $this->haveProduct($productData);

        $productOfferData[ProductOfferTransfer::CONCRETE_SKU] = $productTransfer->getSku();
        $productOfferData[ProductOfferTransfer::STORES] = new ArrayObject($productOfferStoreTransfers);
        $productOfferTransfer = $this->haveProductOffer($productOfferData);

        $servicePointTransfer = $this->createServicePointTransferWithStoreRelations($servicePointStoreNames, $servicePointData);

        $productOfferServiceTransfers = [];
        $servicePointTransfers = [];
        foreach ($servicesData as $serviceData) {
            $serviceData[ServiceTransfer::SERVICE_POINT] = $servicePointTransfer->toArray();
            $serviceTransfer = $this->haveService($serviceData);

            $servicePointTransfers[] = $serviceTransfer;
            $productOfferServiceTransfers[] = $this->haveProductOfferService([
                ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
                ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
            ]);
        }

        if ($productOfferServiceData === []) {
            return [
                $productOfferTransfer,
                $servicePointTransfer,
                $servicePointTransfers,
                $productOfferServiceTransfers,
            ];
        }

        $this->createProductOfferServiceStorageByStoreRelations(
            (new ProductOfferServiceStorageTransfer())->fromArray($productOfferServiceData),
            $productOfferServiceDataStoreNames,
        );

        return [
            $productOfferTransfer,
            $servicePointTransfer,
            $servicePointTransfers,
            $productOfferServiceTransfers,
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    public function createProductOfferServicePointFacadeMock(): ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
    {
        return Stub::makeEmpty(
            ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface::class,
            [
                'iterateProductOfferServices' => [],
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToServicePointFacadeInterface
     */
    public function createServicePointFacadeMock(): ProductOfferServicePointStorageToServicePointFacadeInterface
    {
        return Stub::makeEmpty(
            ProductOfferServicePointStorageToServicePointFacadeInterface::class,
            [
                'getServiceCollection' => new ServiceCollectionTransfer(),
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOfferServicePointStorage\Business\Writer\ProductOfferServiceStorageWriterInterface
     */
    public function createProductOfferServiceStorageWriterMock(): ProductOfferServiceStorageWriterInterface
    {
        return Stub::makeEmpty(
            ProductOfferServiceStorageWriterInterface::class,
        );
    }

    /**
     * @param list<string> $storeNames
     * @param array<string, mixed> $servicePointSeedData
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected function createServicePointTransferWithStoreRelations(
        array $storeNames = [],
        array $servicePointSeedData = []
    ): ServicePointTransfer {
        $storesData = [];
        foreach ($storeNames as $storeName) {
            $storeTransfer = $this->haveStore((new StoreTransfer())->setName($storeName)->toArray());
            $storesData[] = $storeTransfer->toArray();
        }

        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->withStoreRelation([StoreRelationTransfer::STORES => $storesData])
            ->build();

        return $this->haveServicePoint($servicePointTransfer->toArray());
    }

    /**
     * @return \Orm\Zed\ProductOfferServicePointStorage\Persistence\SpyProductOfferServiceStorageQuery
     */
    protected function getProductOfferServiceStorageQuery(): SpyProductOfferServiceStorageQuery
    {
        return SpyProductOfferServiceStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function getProductOfferQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    protected function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
