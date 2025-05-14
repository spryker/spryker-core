<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence;

use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractType;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementPersistenceFactory getFactory()
 */
class SspServiceManagementEntityManager extends AbstractEntityManager implements SspServiceManagementEntityManagerInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createProductShipmentType(int $idProductConcrete, int $idShipmentType): void
    {
        $productShipmentTypeEntity = $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkShipmentType($idShipmentType)
            ->findOneOrCreate();

        $productShipmentTypeEntity->save();
    }

    /**
     * @param int $idProductConcrete
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypesByIdProductConcreteAndShipmentTypeIds(
        int $idProductConcrete,
        array $shipmentTypeIds
    ): void {
        $this->getFactory()
            ->createProductShipmentTypeQuery()
            ->filterByFkProduct($idProductConcrete)
            ->filterByFkShipmentType_In($shipmentTypeIds)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     * @param array<int> $productAbstractTypeIds
     *
     * @return void
     */
    public function saveProductAbstractTypesForProductAbstract(int $idProductAbstract, array $productAbstractTypeIds): void
    {
        $this->deleteProductAbstractTypesByProductAbstractId($idProductAbstract);

        foreach ($productAbstractTypeIds as $idProductAbstractType) {
            $productAbstractToProductAbstractTypeEntity = new SpyProductAbstractToProductAbstractType();
            $productAbstractToProductAbstractTypeEntity
                ->setFkProductAbstract($idProductAbstract)
                ->setFkProductAbstractType($idProductAbstractType)
                ->save();
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteProductAbstractTypesByProductAbstractId(int $idProductAbstract): void
    {
        /**
         * @var \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractType> $productAbstractTypesProductAbstractRelations
         */
        $productAbstractTypesProductAbstractRelations = $this->getFactory()
            ->createProductAbstractToProductAbstractTypeQuery()
            ->filterByFkProductAbstract($idProductAbstract)
            ->find();

        $productAbstractTypesProductAbstractRelations->delete();
    }

    /**
     * @param int $idSalesOrderItem
     * @param string $productTypeName
     *
     * @return void
     */
    public function saveSalesOrderItemProductType(int $idSalesOrderItem, string $productTypeName): void
    {
        $salesProductAbstractTypeEntity = $this->getFactory()
            ->createSalesProductAbstractTypeQuery()
            ->filterByName($productTypeName)
            ->findOneOrCreate();

        if ($salesProductAbstractTypeEntity->isNew()) {
            $salesProductAbstractTypeEntity->save();
        }

        $salesOrderItemProductAbstractTypeEntity = $this->getFactory()
            ->createSalesOrderItemProductAbstractTypeQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->filterByFkSalesProductAbstractType($salesProductAbstractTypeEntity->getIdSalesProductAbstractType())
            ->findOneOrCreate();

        if ($salesOrderItemProductAbstractTypeEntity->isNew()) {
            $salesOrderItemProductAbstractTypeEntity->save();
        }
    }

    /**
     * @param int $idSalesOrderItem
     * @param bool $isServiceDateTimeEnabled
     *
     * @return void
     */
    public function saveIsServiceDateTimeEnabledForSalesOrderItem(int $idSalesOrderItem, bool $isServiceDateTimeEnabled): void
    {
        $salesOrderItemEntity = $this->getFactory()
            ->createSalesOrderItemQuery()
            ->filterByIdSalesOrderItem($idSalesOrderItem)
            ->findOne();

        if ($salesOrderItemEntity) {
            $salesOrderItemEntity->setIsServiceDateTimeEnabled($isServiceDateTimeEnabled);
            $salesOrderItemEntity->save();
        }
    }
}
