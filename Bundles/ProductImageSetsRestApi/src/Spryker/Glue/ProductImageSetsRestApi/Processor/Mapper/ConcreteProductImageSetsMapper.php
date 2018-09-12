<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestImagesAttributesTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;
use Generated\Shared\Transfer\RestProductImageSetTransfer;

class ConcreteProductImageSetsMapper implements ConcreteProductImageSetsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer[] $productImageSetStorageTransfers
     *
     * @return \Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer
     */
    public function mapProductImageStorageTransfersToRestProductImageSetsAttributesTransfer(
        array $productImageSetStorageTransfers
    ): RestProductImageSetsAttributesTransfer {
        $restProductImageSetsAttributesTransfer = new RestProductImageSetsAttributesTransfer();
        foreach ($productImageSetStorageTransfers as $productImageSetStorageTransfer) {
            $restProductImageSet = new RestProductImageSetTransfer();
            $restProductImageSet->setName($productImageSetStorageTransfer->getName());

            foreach ($productImageSetStorageTransfer->getImages() as $images) {
                $restProductImageSet->addImages(
                    (new RestImagesAttributesTransfer())->fromArray(
                        $images->toArray(),
                        true
                    )
                );
            }
            $restProductImageSetsAttributesTransfer->addImageSets($restProductImageSet);
        }

        return $restProductImageSetsAttributesTransfer;
    }
}
