<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer;
use Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig;

class ProductConcreteResourceMapper implements ProductConcreteResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer $productConcreteResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer
     */
    public function mapProductConcreteCollectionTransferToProductConcreteResourceCollectionTransfer(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        ProductConcreteResourceCollectionTransfer $productConcreteResourceCollectionTransfer
    ): ProductConcreteResourceCollectionTransfer {
        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $glueResourceTransfer = $this->mapProductConcreteTransferToGlueResourceTransfer(
                $productConcreteTransfer,
                new GlueResourceTransfer(),
            );

            $productConcreteResourceCollectionTransfer->addProductConcreteResource($glueResourceTransfer);
        }

        return $productConcreteResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapProductConcreteTransferToGlueResourceTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        $productConcretesBackendApiAttributesTransfer = $this->mapProductConcreteTransferToProductConcretesBackendApiAttributesTransfer(
            $productConcreteTransfer,
            new ProductConcretesBackendApiAttributesTransfer(),
        );

        return $glueResourceTransfer
            ->setType(ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS)
            ->setId($productConcreteTransfer->getSkuOrFail())
            ->setAttributes($productConcretesBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer $productConcretesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer
     */
    protected function mapProductConcreteTransferToProductConcretesBackendApiAttributesTransfer(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcretesBackendApiAttributesTransfer $productConcretesBackendApiAttributesTransfer
    ): ProductConcretesBackendApiAttributesTransfer {
        return $productConcretesBackendApiAttributesTransfer->fromArray($productConcreteTransfer->toArray(), true);
    }
}
