<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SspServiceManagement;

use Codeception\Actor;
use Exception;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SspServiceManagement\Persistence\Base\SpySalesOrderItemProductAbstractType;
use Orm\Zed\SspServiceManagement\Persistence\Map\SpyProductAbstractTypeTableMap;
use Orm\Zed\SspServiceManagement\Persistence\Map\SpyProductShipmentTypeTableMap;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractType;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpySalesOrderItemProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpySalesProductAbstractTypeQuery;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class SspServiceManagementCommunicationTester extends Actor
{
    use _generated\SspServiceManagementCommunicationTesterActions;

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
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery
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
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery
     */
    protected function getProductAbstractTypeQuery(): SpyProductAbstractTypeQuery
    {
        return SpyProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery
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

    /**
     * @param array<string, mixed> $seedData
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpySalesOrderItemProductAbstractType
     */
    public function createSalesOrderItemProductAbstractType(array $seedData): SpySalesOrderItemProductAbstractType
    {
        $salesOrderItem = SpySalesOrderItemQuery::create()
            ->filterByIdSalesOrderItem($seedData['id_sales_order_item'])
            ->findOne();

        if (!$salesOrderItem) {
            throw new Exception('Sales order item not found in database. ID: ' . $seedData['id_sales_order_item']);
        }

        $productType = SpySalesProductAbstractTypeQuery::create()
            ->filterByName('test_product_type')
            ->findOneOrCreate();

        if ($productType->isNew() || $productType->isModified()) {
            $productType->save();
        }

        $orderItemProductType = SpySalesOrderItemProductAbstractTypeQuery::create()
            ->filterByFkSalesOrderItem($seedData['id_sales_order_item'])
            ->filterByFkSalesProductAbstractType($productType->getIdSalesProductAbstractType())
            ->findOneOrCreate();

        if ($orderItemProductType->isNew() || $orderItemProductType->isModified()) {
            $orderItemProductType->save();
        }

        return $orderItemProductType;
    }
}
