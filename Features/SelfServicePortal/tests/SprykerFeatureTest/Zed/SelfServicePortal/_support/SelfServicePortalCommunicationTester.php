<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SelfServicePortal;

use ArrayObject;
use Codeception\Actor;
use Exception;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyProductAbstractTypeTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpyProductShipmentTypeTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractType;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductAbstractTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductAbstractTypeQuery;
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
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
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
     * @return void
     */
    public function ensureProductAbstractTypeTableIsEmpty(): void
    {
        $this->getProductAbstractToProductAbstractTypeQuery()->deleteAll();
        $this->getProductAbstractTypeQuery()->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param list<\Generated\Shared\Transfer\ProductAbstractTypeTransfer> $productAbstractTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function addProductAbstractTypesToProductAbstract(
        ProductAbstractTransfer $productAbstractTransfer,
        array $productAbstractTypeTransfers
    ): ProductAbstractTransfer {
        foreach ($productAbstractTypeTransfers as $productAbstractTypeTransfer) {
            $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);
            $this->haveProductAbstractToProductAbstractType(
                $productAbstractTransfer->getIdProductAbstractOrFail(),
                $productAbstractTypeTransfer->getIdProductAbstractTypeOrFail(),
            );
        }

        return $productAbstractTransfer;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractType
     *
     * @return void
     */
    protected function haveProductAbstractToProductAbstractType(int $idProductAbstract, int $idProductAbstractType): void
    {
        $productAbstractToProductAbstractTypeEntity = $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->findOne();

        if (!$productAbstractToProductAbstractTypeEntity) {
            $productAbstractToProductAbstractTypeEntity = new SpyProductAbstractToProductAbstractType();
            $productAbstractToProductAbstractTypeEntity->setFkProductAbstract($idProductAbstract);
            $productAbstractToProductAbstractTypeEntity->setFkProductAbstractType($idProductAbstractType);
            $productAbstractToProductAbstractTypeEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractTypeQuery
     */
    protected function getProductAbstractTypeQuery(): SpyProductAbstractTypeQuery
    {
        return SpyProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductAbstractToProductAbstractTypeQuery
     */
    protected function getProductAbstractToProductAbstractTypeQuery(): SpyProductAbstractToProductAbstractTypeQuery
    {
        return SpyProductAbstractToProductAbstractTypeQuery::create();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getProductAbstractTypeIdsForProductAbstract(int $idProductAbstract): array
    {
        return $this->getProductAbstractTypeQuery()
            ->useProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->select([SpyProductAbstractTypeTableMap::COL_ID_PRODUCT_ABSTRACT_TYPE])
            ->find()
            ->toArray();
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
     * @return void
     */
    public function cleanUpSalesOrderItemProductTypeRelations(): void
    {
        SpySalesOrderItemProductAbstractTypeQuery::create()->deleteAll();

        SpySalesProductAbstractTypeQuery::create()
            ->filterByName_In(['service', 'additional_type'])
            ->delete();
    }

    /**
     * @param string $productTypeName
     * @param int $idSalesOrderItem
     *
     * @throws \Exception
     *
     * @return void
     */
    public function haveSalesOrderItemProductType(string $productTypeName, int $idSalesOrderItem): void
    {
        $salesOrderItem = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if (!$salesOrderItem) {
            throw new Exception('Sales order item not found in database. ID: ' . $idSalesOrderItem);
        }

        $productType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName($productTypeName)
            ->findOneOrCreate();

        if ($productType->isNew() || $productType->isModified()) {
            $productType->save();
        }

        $orderItemProductType = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductAbstractType($productType->getIdSalesProductAbstractType())
            ->findOneOrCreate();

        if ($orderItemProductType->isNew() || $orderItemProductType->isModified()) {
            $orderItemProductType->save();
        }
    }
}
