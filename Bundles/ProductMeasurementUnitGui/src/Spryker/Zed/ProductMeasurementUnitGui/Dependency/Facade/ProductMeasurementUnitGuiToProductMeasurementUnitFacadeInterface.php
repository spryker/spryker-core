<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer;

interface ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer $productMeasurementUnitCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionTransfer
     */
    public function getProductMeasurementUnitCollection(
        ProductMeasurementUnitCriteriaTransfer $productMeasurementUnitCriteriaTransfer
    ): ProductMeasurementUnitCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function deleteProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function createProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function updateProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer;
}
