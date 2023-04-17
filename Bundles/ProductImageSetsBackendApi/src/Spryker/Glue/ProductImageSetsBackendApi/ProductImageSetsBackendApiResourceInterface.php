<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi;

use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;

interface ProductImageSetsBackendApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves concrete product image set resources by criteria.
     * - Returns collection of product image set rest resources.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    public function getConcreteProductImageSetResourceCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetResourceCollectionTransfer;
}
