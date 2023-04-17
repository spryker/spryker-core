<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi;

use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;

/**
 * @method \Spryker\Glue\ProductsBackendApi\ProductsBackendApiFactory getFactory()
 */
class ProductsBackendApiResource extends AbstractRestResource implements ProductsBackendApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer
     */
    public function getProductConcreteResourceCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteResourceCollectionTransfer {
        return $this->getFactory()
            ->createProductConcreteResourceReader()
            ->getProductConcreteResourceCollection($productConcreteCriteriaTransfer);
    }
}
