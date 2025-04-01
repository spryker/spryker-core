<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence;

interface SspServiceManagementEntityManagerInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idShipmentType
     *
     * @return void
     */
    public function createProductShipmentType(int $idProductConcrete, int $idShipmentType): void;

    /**
     * @param int $idProductConcrete
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    public function deleteProductShipmentTypesByIdProductConcreteAndShipmentTypeIds(
        int $idProductConcrete,
        array $shipmentTypeIds
    ): void;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deleteProductAbstractTypesByProductAbstractId(int $idProductAbstract): void;

    /**
     * @param int $idProductAbstract
     * @param array<int> $productAbstractTypeIds
     *
     * @return void
     */
    public function saveProductAbstractTypesForProductAbstract(int $idProductAbstract, array $productAbstractTypeIds): void;

    /**
     * @param int $idSalesOrderItem
     * @param string $productTypeName
     *
     * @return void
     */
    public function saveSalesOrderItemProductType(int $idSalesOrderItem, string $productTypeName): void;
}
