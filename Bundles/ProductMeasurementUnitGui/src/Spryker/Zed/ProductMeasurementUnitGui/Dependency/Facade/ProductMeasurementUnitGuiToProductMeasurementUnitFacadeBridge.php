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

class ProductMeasurementUnitGuiToProductMeasurementUnitFacadeBridge implements ProductMeasurementUnitGuiToProductMeasurementUnitFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct($productMeasurementUnitFacade)
    {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCriteriaTransfer $productMeasurementUnitCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionTransfer
     */
    public function getProductMeasurementUnitCollection(
        ProductMeasurementUnitCriteriaTransfer $productMeasurementUnitCriteriaTransfer
    ): ProductMeasurementUnitCollectionTransfer {
        return $this->productMeasurementUnitFacade->getProductMeasurementUnitCollection($productMeasurementUnitCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function deleteProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionDeleteCriteriaTransfer $productMeasurementUnitCollectionDeleteCriteriaTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->productMeasurementUnitFacade->deleteProductMeasurementUnitCollection($productMeasurementUnitCollectionDeleteCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function createProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->productMeasurementUnitFacade->createProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function updateProductMeasurementUnitCollection(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer {
        return $this->productMeasurementUnitFacade->updateProductMeasurementUnitCollection($productMeasurementUnitCollectionRequestTransfer);
    }
}
