<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer;

class ProductMeasurementUnitMapper implements ProductMeasurementUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function mapProductMeasurementSalesUnitTransfer(
        SpyProductMeasurementSalesUnitEntityTransfer $productMeasurementSalesUnitEntityTransfer,
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
    ): ProductMeasurementSalesUnitTransfer {
        $productMeasurementSalesUnitTransfer->fromArray(
            $productMeasurementSalesUnitEntityTransfer->toArray(),
            true
        );

        return $productMeasurementSalesUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer $productMeasurementBaseUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function mapProductMeasurementBaseUnitTransfer(
        SpyProductMeasurementBaseUnitEntityTransfer $productMeasurementBaseUnitEntityTransfer,
        ProductMeasurementBaseUnitTransfer $productMeasurementBaseUnitTransfer
    ): ProductMeasurementBaseUnitTransfer {
        $productMeasurementBaseUnitTransfer->fromArray(
            $productMeasurementBaseUnitEntityTransfer->toArray(),
            true
        );

        return $productMeasurementBaseUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntityTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    public function mapProductMeasurementUnitTransfer(
        SpyProductMeasurementUnitEntityTransfer $productMeasurementUnitEntityTransfer,
        ProductMeasurementUnitTransfer $productMeasurementUnitTransfer
    ): ProductMeasurementUnitTransfer {
        $productMeasurementUnitTransfer->fromArray(
            $productMeasurementUnitEntityTransfer->toArray(),
            true
        );

        return $productMeasurementUnitTransfer;
    }
}
