<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiProductImageSetsAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer;
use Generated\Shared\Transfer\ApiProductsImageSetImageAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiConfig;

class ProductImageSetResourceMapper implements ProductImageSetResourceMapperInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductImageSetTransfer>> $productImageSetTransfersGroupedBySku
     * @param \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    public function mapProductImageSetCollectionToProductImageSetResourceCollection(
        array $productImageSetTransfersGroupedBySku,
        ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
    ): ProductImageSetResourceCollectionTransfer {
        foreach ($productImageSetTransfersGroupedBySku as $productImageSetTransfers) {
            $productImageSetResourceCollectionTransfer->addProductImageSetResource(
                $this->mapProductImageSetTransfersToGlueResourceTransfer($productImageSetTransfers, new GlueResourceTransfer()),
            );
        }

        return $productImageSetResourceCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapProductImageSetTransfersToGlueResourceTransfer(
        array $productImageSetTransfers,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        $apiProductImageSetsAttributesTransfer = new ApiProductImageSetsAttributesTransfer();
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $apiProductsImageSetAttributesTransfer = (new ApiProductsImageSetAttributesTransfer())
                ->fromArray($productImageSetTransfer->toArray(), true)
                ->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());

            $apiProductsImageSetAttributesTransfer = $this->mapProductImageTransfersToApiProductsImageSetAttributes(
                $apiProductsImageSetAttributesTransfer,
                $productImageSetTransfer,
            );

            $apiProductImageSetsAttributesTransfer->addImageSets($apiProductsImageSetAttributesTransfer);
        }

        return $glueResourceTransfer
            ->setType(ProductImageSetsBackendApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS)
            ->setId($productImageSetTransfers[0]->getSku())
            ->setAttributes($apiProductImageSetsAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer $apiProductsImageSetAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer
     */
    protected function mapProductImageTransfersToApiProductsImageSetAttributes(
        ApiProductsImageSetAttributesTransfer $apiProductsImageSetAttributesTransfer,
        ProductImageSetTransfer $productImageSetTransfer
    ): ApiProductsImageSetAttributesTransfer {
        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $apiProductsImageSetAttributesTransfer->addImage(
                (new ApiProductsImageSetImageAttributesTransfer())->fromArray($productImageTransfer->toArray(), true),
            );
        }

        return $apiProductsImageSetAttributesTransfer;
    }
}
