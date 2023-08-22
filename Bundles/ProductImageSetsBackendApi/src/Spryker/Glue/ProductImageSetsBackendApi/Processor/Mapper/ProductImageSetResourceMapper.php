<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductImageSetImagesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetsBackendApiAttributesTransfer;
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
        $productImageSetsBackendApiAttributesTransfer = new ProductImageSetsBackendApiAttributesTransfer();
        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $productImageSetBackendApiAttributesTransfer = (new ProductImageSetBackendApiAttributesTransfer())
                ->fromArray($productImageSetTransfer->toArray(), true)
                ->setLocale($productImageSetTransfer->getLocaleOrFail()->getLocaleNameOrFail());

            $productImageSetBackendApiAttributesTransfer = $this->mapProductImageTransfersToProductImageSetBackendApiAttributesTransfer(
                $productImageSetBackendApiAttributesTransfer,
                $productImageSetTransfer,
            );

            $productImageSetsBackendApiAttributesTransfer->addImageSets($productImageSetBackendApiAttributesTransfer);
        }

        return $glueResourceTransfer
            ->setType(ProductImageSetsBackendApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS)
            ->setId($productImageSetTransfers[0]->getSku())
            ->setAttributes($productImageSetsBackendApiAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer $productImageSetBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer
     */
    protected function mapProductImageTransfersToProductImageSetBackendApiAttributesTransfer(
        ProductImageSetBackendApiAttributesTransfer $productImageSetBackendApiAttributesTransfer,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetBackendApiAttributesTransfer {
        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $productImageSetBackendApiAttributesTransfer->addImage(
                (new ProductImageSetImagesBackendApiAttributesTransfer())->fromArray($productImageTransfer->toArray(), true),
            );
        }

        return $productImageSetBackendApiAttributesTransfer;
    }
}
