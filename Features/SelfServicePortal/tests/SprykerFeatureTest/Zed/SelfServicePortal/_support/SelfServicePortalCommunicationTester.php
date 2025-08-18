<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductListBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\ProductList\Persistence\Base\SpyProductListQuery;
use Orm\Zed\ProductList\Persistence\SpyProductList;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyProductShipmentTypeTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClass;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelToProductListQuery;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @SuppressWarnings(PHPMD)
 */
class SelfServicePortalCommunicationTester extends Actor
{
    use _generated\SelfServicePortalCommunicationTesterActions;

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID = 'test-service-point-uuid';

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_UUID = 'test-shipment-type-uuid';

    /**
     * @var int
     */
    protected const TEST_SHIPMENT_TYPE_ID = 1;

    /**
     * @var string
     */
    protected const TEST_SHIPMENT_TYPE_NAME = 'Test Shipment Type';

    /**
     * @var string
     */
    public const TEST_ASSET_REFERENCE = 'test-asset-reference';

    /**
     * @var string
     */
    public const TEST_ASSET_NAME = 'Test Asset';

    /**
     * @var string
     */
    public const TEST_ASSET_SERIAL_NUMBER = 'SN123456789';

    /**
     * @var string
     */
    public const TEST_ASSET_REFERENCE_2 = 'test-asset-reference-2';

    /**
     * @var string
     */
    public const TEST_ASSET_NAME_2 = 'Test Asset 2';

    /**
     * @var string
     */
    public const TEST_ASSET_SERIAL_NUMBER_2 = 'SN987654321';

    public function ensureProductShipmentTypeTableIsEmpty(): void
    {
        $this->getProductShipmentTypeQuery()
            ->find()
            ->delete();
    }

    public function hasProductClass(ItemTransfer $item, string $productClassName): bool
    {
        foreach ($item->getProductClasses() as $productClass) {
            if ($productClass->getName() === $productClassName) {
                return true;
            }
        }

        return false;
    }

    public function createCartChangeTransferWithSspAsset(string $assetReference): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer->setReference($assetReference);
        $itemTransfer->setSspAsset($sspAssetTransfer);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    public function createCartChangeTransferWithoutSspAsset(): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    public function createEmptyCartChangeTransfer(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    public function createSspAssetCollectionTransfer(): SspAssetCollectionTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer
            ->setReference(static::TEST_ASSET_REFERENCE)
            ->setName(static::TEST_ASSET_NAME)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER)
            ->setStatus('ACTIVE');

        $sspAssetCollectionTransfer = new SspAssetCollectionTransfer();
        $sspAssetCollectionTransfer->addSspAsset($sspAssetTransfer);

        return $sspAssetCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer|null $returnValue
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface
     */
    public function createSelfServicePortalRepositoryMock(?SspAssetCollectionTransfer $returnValue = null): MockObject
    {
        $mockBuilder = $this->getMockBuilder(SelfServicePortalRepositoryInterface::class);
        $mockBuilder->disableOriginalConstructor();

        $mock = $mockBuilder->getMock();

        if ($returnValue !== null) {
            $mock->method('getSspAssetCollection')
                ->willReturn($returnValue);
        }

        return $mock;
    }

    public function createOrderTransferWithItems(): OrderTransfer
    {
        $itemTransfer1 = new ItemTransfer();
        $itemTransfer1->setIdSalesOrderItem(1);

        $itemTransfer2 = new ItemTransfer();
        $itemTransfer2->setIdSalesOrderItem(2);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject([$itemTransfer1, $itemTransfer2]));

        return $orderTransfer;
    }

    public function createEmptyOrderTransfer(): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject());

        return $orderTransfer;
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function createSspAssetTransfersIndexedBySalesOrderItemId(): array
    {
        $sspAssetTransfer1 = new SspAssetTransfer();
        $sspAssetTransfer1
            ->setReference(static::TEST_ASSET_REFERENCE)
            ->setName(static::TEST_ASSET_NAME)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER)
            ->setStatus('ACTIVE');

        $sspAssetTransfer2 = new SspAssetTransfer();
        $sspAssetTransfer2
            ->setReference(static::TEST_ASSET_REFERENCE_2)
            ->setName(static::TEST_ASSET_NAME_2)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER_2)
            ->setStatus('ACTIVE');

        return [
            1 => $sspAssetTransfer1,
            2 => $sspAssetTransfer2,
        ];
    }

    public function ensureProductShipmentTypeRelationExists(int $idProduct, int $idShipmentType): bool
    {
        $productShipmentTypeQuery = $this->getProductShipmentTypeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkShipmentType($idShipmentType);

        return $productShipmentTypeQuery->exists();
    }

    /**
     * @param int $idProduct
     *
     * @return list<int>
     */
    public function getProductShipmentTypeIds(int $idProduct): array
    {
        return $this->getProductShipmentTypeQuery()
            ->filterByFkProduct($idProduct)
            ->select([SpyProductShipmentTypeTableMap::COL_FK_SHIPMENT_TYPE])
            ->find()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param list<\Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function addShipmentTypesToProduct(
        ProductConcreteTransfer $productConcreteTransfer,
        array $shipmentTypeTransfers
    ): ProductConcreteTransfer {
        foreach ($shipmentTypeTransfers as $shipmentTypeTransfer) {
            $productConcreteTransfer->addShipmentType($shipmentTypeTransfer);
            $this->haveProductConcreteShipmentType($productConcreteTransfer, $shipmentTypeTransfer);
        }

        return $productConcreteTransfer;
    }

    protected function getProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointCollectionTransfer(): ServicePointCollectionTransfer
    {
        $servicePointTransfer = new ServicePointTransfer();
        $servicePointTransfer->setUuid(static::TEST_SERVICE_POINT_UUID);
        $servicePointTransfer->setIdServicePoint(1);
        $servicePointTransfer->setName('Test Service Point');

        $servicePointCollection = new ServicePointCollectionTransfer();
        $servicePointCollection->addServicePoint($servicePointTransfer);

        return $servicePointCollection;
    }

    public function ensureSalesOrderItemProductClassTableIsEmpty(): void
    {
        $this->getSalesOrderItemProductClassQuery()
            ->find()
            ->delete();
    }

    public function ensureSalesOrderItemSspAssetTableIsEmpty(): void
    {
        $this->getSalesOrderItemSspAssetQuery()
            ->find()
            ->delete();
    }

    public function countSalesOrderItemProductClasses(): int
    {
        return $this->getSalesOrderItemProductClassQuery()->count();
    }

    public function countSalesOrderItemSspAssets(): int
    {
        return $this->getSalesOrderItemSspAssetQuery()->count();
    }

    public function findSalesOrderItemProductClass(int $idSalesOrderItem, string $name): ?SpySalesOrderItemProductClass
    {
        return $this->getSalesOrderItemProductClassQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->useSpySalesProductClassQuery()
                ->filterByName($name)
            ->endUse()
            ->findOne();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return array<string>
     */
    public function getProductClassNamesByIdProductConcrete(int $idProductConcrete): array
    {
        $productClassEntities = $this->getProductClassQuery()
            ->useProductToProductClassQuery()
                ->filterByFkProduct($idProductConcrete)
            ->endUse()
            ->find();

        $productClassNames = [];
        foreach ($productClassEntities as $productClassEntity) {
            $productClassNames[] = $productClassEntity->getName();
        }

        return $productClassNames;
    }

    public function createShipmentTypeTransfer(
        string $uuid = self::TEST_SHIPMENT_TYPE_UUID,
        int $id = self::TEST_SHIPMENT_TYPE_ID,
        string $name = self::TEST_SHIPMENT_TYPE_NAME
    ): ShipmentTypeTransfer {
        $shipmentTypeTransfer = new ShipmentTypeTransfer();
        $shipmentTypeTransfer->setUuid($uuid);
        $shipmentTypeTransfer->setIdShipmentType($id);
        $shipmentTypeTransfer->setName($name);

        return $shipmentTypeTransfer;
    }

    public function findSalesOrderItemSspAsset(int $idSalesOrderItem, string $assetReference): ?SpySalesOrderItemSspAsset
    {
        return $this->getSalesOrderItemSspAssetQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByReference($assetReference)
            ->findOne();
    }

    public function ensureProductClassTableIsEmpty(): void
    {
        $this->truncateProductClassTable();
    }

    public function truncateProductClassTable(): void
    {
        $this->getProductToProductClassQuery()->deleteAll();
        $this->getProductClassQuery()->deleteAll();
    }

    /**
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductClass>
     */
    public function getAllProductClasses(): array
    {
        return $this->getProductClassQuery()->find()->getArrayCopy();
    }

    protected function getProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    protected function getProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    protected function getSalesOrderItemProductClassQuery(): SpySalesOrderItemProductClassQuery
    {
        return SpySalesOrderItemProductClassQuery::create();
    }

    protected function getSalesOrderItemSspAssetQuery(): SpySalesOrderItemSspAssetQuery
    {
        return SpySalesOrderItemSspAssetQuery::create();
    }

    public function ensureSspModelTableIsEmpty(): void
    {
        $this->getSspModelQuery()->deleteAll();
    }

    public function truncateSspModelTable(): void
    {
        $this->getSspModelQuery()->deleteAll();
    }

    /**
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspModel>
     */
    public function getAllSspModels(): array
    {
        return $this->getSspModelQuery()->find()->getData();
    }

    public function ensureSspModelToProductListTableIsEmpty(): void
    {
        $this->getSspModelToProductListQuery()->deleteAll();
    }

    /**
     * @param int $sspModelId
     *
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspModelToProductList>
     */
    public function getSspModelToProductListRelations(int $sspModelId): array
    {
        return $this->getSspModelToProductListQuery()
            ->filterByFkSspModel($sspModelId)
            ->joinWithSpySspModel()
            ->joinWithSpyProductList()
            ->find()
            ->getData();
    }

    /**
     * @param int $sspModelId
     *
     * @return array<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModel>
     */
    public function getSspModelAssetRelations(int $sspModelId): array
    {
        return $this->getSspAssetToSspModelQuery()
            ->filterByFkSspModel($sspModelId)
            ->joinWithSpySspAsset()
            ->joinWithSpySspModel()
            ->find()
            ->getData();
    }

    protected function getSspModelQuery(): SpySspModelQuery
    {
        return SpySspModelQuery::create();
    }

    protected function getSspModelToProductListQuery(): SpySspModelToProductListQuery
    {
        return SpySspModelToProductListQuery::create();
    }

    protected function getSspAssetToSspModelQuery(): SpySspAssetToSspModelQuery
    {
        return SpySspAssetToSspModelQuery::create();
    }

    public function haveProductList(array $seed = []): ProductListTransfer
    {
        $productListTransfer = (new ProductListBuilder($seed))->build();

        $productListEntity = (new SpyProductList())->fromArray($productListTransfer->toArray());

        $productListEntity->save();
        $productListTransfer->setIdProductList($productListEntity->getIdProductList());

        return $productListTransfer;
    }

    public function truncateProductListTable(array $keys = []): void
    {
        SpyProductListQuery::create()->filterByKey_In($keys)->delete();
    }

    public function isSspModelAssetRelationExists(int $sspModelId, int $sspAssetId): bool
    {
        return SpySspAssetToSspModelQuery::create()
            ->filterByFkSspModel($sspModelId)
            ->filterByFkSspAsset($sspAssetId)
            ->exists();
    }

    public function isSspModelProductListRelationExists(int $modelId, int $productListId): bool
    {
        return SpySspModelToProductListQuery::create()
            ->filterByFkSspModel($modelId)
            ->filterByFkProductList($productListId)
            ->exists();
    }

    public function ensureSspAssetToSspModelTableIsEmpty(): void
    {
        $this->getSspAssetToSspModelQuery()->deleteAll();
    }
}
