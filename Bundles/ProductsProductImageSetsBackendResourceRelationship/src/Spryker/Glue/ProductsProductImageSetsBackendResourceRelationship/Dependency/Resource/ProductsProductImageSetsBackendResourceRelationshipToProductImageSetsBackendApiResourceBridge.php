<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;

class ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceBridge implements ProductsProductImageSetsBackendResourceRelationshipToProductImageSetsBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiResourceInterface
     */
    protected $productImageSetsBackendApiResource;

    /**
     * @param \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiResourceInterface $productImageSetsBackendApiResource
     */
    public function __construct($productImageSetsBackendApiResource)
    {
        $this->productImageSetsBackendApiResource = $productImageSetsBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    public function getConcreteProductImageSetResourceCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetResourceCollectionTransfer {
        return $this->productImageSetsBackendApiResource->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);
    }
}
