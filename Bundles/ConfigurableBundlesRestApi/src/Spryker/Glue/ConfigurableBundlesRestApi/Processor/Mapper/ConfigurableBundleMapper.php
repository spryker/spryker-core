<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleImagesAttributesTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer;

class ConfigurableBundleMapper implements ConfigurableBundleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer $restConfigurableBundleTemplatesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer
     */
    public function mapConfigurableBundleTemplateStorageTransferToRestAttributesTransfer(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        RestConfigurableBundleTemplatesAttributesTransfer $restConfigurableBundleTemplatesAttributesTransfer
    ): RestConfigurableBundleTemplatesAttributesTransfer {
        $restConfigurableBundleTemplatesAttributesTransfer->fromArray(
            $configurableBundleTemplateStorageTransfer->toArray(),
            true
        );

        return $restConfigurableBundleTemplatesAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer $restConfigurableBundleTemplateSlotsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplateSlotsAttributesTransfer
     */
    public function mapConfigurableBundleTemplateSlotStorageTransferToRestAttributesTransfer(
        ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer,
        RestConfigurableBundleTemplateSlotsAttributesTransfer $restConfigurableBundleTemplateSlotsAttributesTransfer
    ): RestConfigurableBundleTemplateSlotsAttributesTransfer {
        $restConfigurableBundleTemplateSlotsAttributesTransfer->fromArray(
            $configurableBundleTemplateSlotStorageTransfer->toArray(),
            true
        );

        return $restConfigurableBundleTemplateSlotsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer $productImageSetStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer $restConfigurableBundleTemplateImageSetsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer
     */
    public function mapProductImageSetStorageTransferToRestAttributesTransfer(
        ProductImageSetStorageTransfer $productImageSetStorageTransfer,
        RestConfigurableBundleTemplateImageSetsAttributesTransfer $restConfigurableBundleTemplateImageSetsAttributesTransfer
    ): RestConfigurableBundleTemplateImageSetsAttributesTransfer {
        $restConfigurableBundleTemplateImageSetsAttributesTransfer->fromArray(
            $productImageSetStorageTransfer->toArray(),
            true
        );

        foreach ($productImageSetStorageTransfer->getImages() as $productImageStorageTransfer) {
            $restConfigurableBundleImagesAttributesTransfer = $this->mapProductImageStorageTransferToRestAttributesTransfer(
                $productImageStorageTransfer,
                new RestConfigurableBundleImagesAttributesTransfer()
            );

            $restConfigurableBundleTemplateImageSetsAttributesTransfer->addImage($restConfigurableBundleImagesAttributesTransfer);
        }

        return $restConfigurableBundleTemplateImageSetsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageStorageTransfer $productImageStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleImagesAttributesTransfer $restConfigurableBundleImagesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleImagesAttributesTransfer
     */
    protected function mapProductImageStorageTransferToRestAttributesTransfer(
        ProductImageStorageTransfer $productImageStorageTransfer,
        RestConfigurableBundleImagesAttributesTransfer $restConfigurableBundleImagesAttributesTransfer
    ) {
        $restConfigurableBundleImagesAttributesTransfer->fromArray(
            $productImageStorageTransfer->toArray(),
            true
        );

        return $restConfigurableBundleImagesAttributesTransfer;
    }
}
