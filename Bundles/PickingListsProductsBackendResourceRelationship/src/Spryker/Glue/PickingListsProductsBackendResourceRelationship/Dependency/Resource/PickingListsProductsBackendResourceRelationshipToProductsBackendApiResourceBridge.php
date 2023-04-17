<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsProductsBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer;

class PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceBridge implements PickingListsProductsBackendResourceRelationshipToProductsBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ProductsBackendApi\ProductsBackendApiResourceInterface
     */
    protected $productsBackendApiResource;

    /**
     * @param \Spryker\Glue\ProductsBackendApi\ProductsBackendApiResourceInterface $productsBackendApiResource
     */
    public function __construct($productsBackendApiResource)
    {
        $this->productsBackendApiResource = $productsBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer
     */
    public function getProductConcreteResourceCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteResourceCollectionTransfer {
        return $this->productsBackendApiResource->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);
    }
}
