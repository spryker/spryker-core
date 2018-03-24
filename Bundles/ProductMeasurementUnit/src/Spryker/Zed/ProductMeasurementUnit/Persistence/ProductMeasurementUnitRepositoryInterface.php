<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

interface ProductMeasurementUnitRepositoryInterface
{
    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntityByIdProduct($idProduct);

    /**
     * @api
     *
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct);

    /**
     * @api
     *
     * @param int $idProductMeasurementBaseUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntity($idProductMeasurementBaseUnit);

    /**
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function getProductMeasurementUnitEntities(array $productMeasurementUnitIds);

    /**
     * @api
     *
     * @return string[] Keys are product measurement unit IDs, values are product measurement unit codes.
     */
    public function getProductMeasurementUnitCodeMap();

    /**
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntities($productIds);
}
