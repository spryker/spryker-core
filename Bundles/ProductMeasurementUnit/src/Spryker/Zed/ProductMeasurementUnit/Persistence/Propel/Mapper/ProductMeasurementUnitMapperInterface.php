<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit;

interface ProductMeasurementUnitMapperInterface
{
    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function mapProductMeasurementSalesUnitTransfer(
        SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer;

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit $productMeasurementBaseUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function mapProductMeasurementBaseUnitTransfer(
        SpyProductMeasurementBaseUnit $productMeasurementBaseUnitEntity,
        ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
    ): ProductMeasurementBaseUnitTransfer;

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnit $productMeasurementUnitEntity
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function mapProductMeasurementUnitTransfer(
        SpyProductMeasurementUnit $productMeasurementUnitEntity,
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): ProductMeasurementUnitTransfer;
}
