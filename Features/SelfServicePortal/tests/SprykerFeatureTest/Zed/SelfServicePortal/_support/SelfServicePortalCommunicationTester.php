<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyProductShipmentTypeTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClass;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
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

    /**
     * @return void
     */
    public function ensureProductShipmentTypeTableIsEmpty(): void
    {
        $this->getProductShipmentTypeQuery()
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param string $productClassName
     *
     * @return bool
     */
    public function hasProductClass(ItemTransfer $item, string $productClassName): bool
    {
        foreach ($item->getProductClasses() as $productClass) {
            if ($productClass->getName() === $productClassName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $assetReference
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
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

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithoutSspAsset(): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createEmptyCartChangeTransfer(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
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

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
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

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
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

    /**
     * @param int $idProduct
     * @param int $idShipmentType
     *
     * @return bool
     */
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

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery
     */
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

    /**
     * @param string $uuid
     * @param int $id
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */

    /**
     * @return void
     */
    public function ensureSalesOrderItemProductClassTableIsEmpty(): void
    {
        $this->getSalesOrderItemProductClassQuery()
            ->find()
            ->delete();
    }

    /**
     * @return void
     */
    public function ensureSalesOrderItemSspAssetTableIsEmpty(): void
    {
        $this->getSalesOrderItemSspAssetQuery()
            ->find()
            ->delete();
    }

    /**
     * @return int
     */
    public function countSalesOrderItemProductClasses(): int
    {
        return $this->getSalesOrderItemProductClassQuery()->count();
    }

    /**
     * @return int
     */
    public function countSalesOrderItemSspAssets(): int
    {
        return $this->getSalesOrderItemSspAssetQuery()->count();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $name
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClass|null
     */
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

    /**
     * @param string $uuid
     * @param int $id
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
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

    /**
     * @param int $idSalesOrderItem
     * @param string $assetReference
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAsset|null
     */
    public function findSalesOrderItemSspAsset(int $idSalesOrderItem, string $assetReference): ?SpySalesOrderItemSspAsset
    {
        return $this->getSalesOrderItemSspAssetQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByReference($assetReference)
            ->findOne();
    }

    /**
     * @return void
     */
    public function ensureProductClassTableIsEmpty(): void
    {
        $this->truncateProductClassTable();
    }

    /**
     * @return void
     */
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

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery
     */
    protected function getProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery
     */
    protected function getProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery
     */
    protected function getSalesOrderItemProductClassQuery(): SpySalesOrderItemProductClassQuery
    {
        return SpySalesOrderItemProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery
     */
    protected function getSalesOrderItemSspAssetQuery(): SpySalesOrderItemSspAssetQuery
    {
        return SpySalesOrderItemSspAssetQuery::create();
    }
}
