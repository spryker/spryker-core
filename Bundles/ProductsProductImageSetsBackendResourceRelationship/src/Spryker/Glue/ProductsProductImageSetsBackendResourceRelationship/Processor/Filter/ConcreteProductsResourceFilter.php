<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Processor\Filter;

use Generated\Shared\Transfer\GlueResourceTransfer;

class ConcreteProductsResourceFilter implements ConcreteProductsResourceFilterInterface
{
    /**
     * @uses \Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterConcreteProductResources(array $glueResourceTransfers): array
    {
        $concreteProductsResourceTransfers = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if (!$this->isApplicableConcreteProductsResource($glueResourceTransfer)) {
                continue;
            }

            $concreteProductsResourceTransfers[] = $glueResourceTransfer;
        }

        return $concreteProductsResourceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicableConcreteProductsResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === static::RESOURCE_CONCRETE_PRODUCTS;
    }
}
