<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Shared\SspServiceManagement\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractTypeBuilder;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySalesOrderItemSspAsset;
use Orm\Zed\SspAssetManagement\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentType;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class SspServiceManagementHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return void
     */
    public function haveProductConcreteShipmentType(
        ProductConcreteTransfer $productConcreteTransfer,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): void {
        $productShipmentTypeEntity = $this->createProductShipmentTypeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcreteOrFail())
            ->filterByFkShipmentType($shipmentTypeTransfer->getIdShipmentTypeOrFail())
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productShipmentTypeEntity): void {
            $this->deleteProductConcreteShipmentType($productShipmentTypeEntity);
        });
    }

    /**
     * @return void
     */
    public function ensureProductShipmentTypeTableIsEmpty(): void
    {
        $this->createProductShipmentTypeQuery()->deleteAll();
    }

    /**
     * @param array<string, mixed> $productAbstractTypeOverride
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTypeTransfer
     */
    public function haveProductAbstractType(array $productAbstractTypeOverride = []): ProductAbstractTypeTransfer
    {
        $productAbstractTypeTransfer = (new ProductAbstractTypeBuilder($productAbstractTypeOverride))
            ->build();

        $productAbstractTypeEntity = $this->getProductAbstractTypeQuery()
            ->filterByKey($productAbstractTypeTransfer->getKey())
            ->findOneOrCreate();

        $productAbstractTypeEntity->fromArray($productAbstractTypeTransfer->modifiedToArray());
        if ($productAbstractTypeEntity->isNew() || $productAbstractTypeEntity->isModified()) {
            $productAbstractTypeEntity->save();
        }

        $productAbstractTypeTransfer->setIdProductAbstractType($productAbstractTypeEntity->getIdProductAbstractType());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractTypeTransfer): void {
            $this->cleanupProductAbstractType($productAbstractTypeTransfer->getIdProductAbstractType());
        });

        return $productAbstractTypeTransfer;
    }

    /**
     * @param int $idProductAbstractType
     *
     * @return void
     */
    protected function cleanupProductAbstractType(int $idProductAbstractType): void
    {
        $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->delete();

        $this->getProductAbstractTypeQuery()
            ->filterByIdProductAbstractType($idProductAbstractType)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractType
     *
     * @return void
     */
    public function haveProductAbstractToProductAbstractType(
        int $idProductAbstract,
        int $idProductAbstractType
    ): void {
        $productAbstractToProductAbstractTypeEntity = $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->findOneOrCreate();

        if ($productAbstractToProductAbstractTypeEntity->isNew() || $productAbstractToProductAbstractTypeEntity->isModified()) {
            $productAbstractToProductAbstractTypeEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idProductAbstract, $idProductAbstractType): void {
            $this->cleanupProductAbstractToProductAbstractType(
                $idProductAbstract,
                $idProductAbstractType,
            );
        });
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSspAsset
     *
     * @return void
     */
    public function haveSalesSspAsset(int $idSalesOrder, int $idSspAsset): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        $sspAssetEntity = $this->getSspAssetQuery()
            ->filterByIdSspAsset($idSspAsset)
            ->findOne();

        if ($salesOrderEntity === null || $sspAssetEntity === null) {
            return;
        }

        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            (new SpySalesOrderItemSspAsset())
            ->setName($sspAssetEntity->getName())
            ->setReference($sspAssetEntity->getReference())
            ->setSerialNumber($sspAssetEntity->getSerialNumber())
            ->setFkSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem())
            ->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idSalesOrder, $idSspAsset): void {
            $this->cleanupSalesSspAsset(
                $idSalesOrder,
                $idSspAsset,
            );
        });
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSspAsset
     *
     * @return void
     */
    protected function cleanupSalesSspAsset(int $idSalesOrder, int $idSspAsset): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()
            ->filterByIdSalesOrder($idSalesOrder)
            ->findOne();

        if ($salesOrderEntity === null) {
            return;
        }

        $sspAssetEntity = $this->getSspAssetQuery()
            ->filterByIdSspAsset($idSspAsset)
            ->findOne();

        if ($sspAssetEntity === null) {
            return;
        }

        $salesOrderItemIds = [];
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $salesOrderItemIds[] = $salesOrderItemEntity->getIdSalesOrderItem();
        }

        if ($salesOrderItemIds) {
            SpySalesOrderItemSspAssetQuery::create()
                ->filterByFkSalesOrderItem_In($salesOrderItemIds)
                ->filterByReference($sspAssetEntity->getReference())
                ->delete();
        }
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductAbstractType
     *
     * @return void
     */
    protected function cleanupProductAbstractToProductAbstractType(int $idProductAbstract, int $idProductAbstractType): void
    {
        $this->getProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductAbstractType($idProductAbstractType)
            ->delete();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery
     */
    protected function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    /**
     * @param \Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentType $productShipmentTypeEntity
     *
     * @return void
     */
    protected function deleteProductConcreteShipmentType(SpyProductShipmentType $productShipmentTypeEntity): void
    {
        $this->createProductShipmentTypeQuery()
            ->filterByIdProductShipmentType($productShipmentTypeEntity->getIdProductShipmentType())
            ->delete();
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    protected function getSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }
}
