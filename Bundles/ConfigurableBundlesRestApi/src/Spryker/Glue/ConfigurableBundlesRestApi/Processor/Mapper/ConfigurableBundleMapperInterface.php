<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer;

interface ConfigurableBundleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetStorageTransfer $productImageSetStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer $restConfigurableBundleTemplateImageSetsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplateImageSetsAttributesTransfer
     */
    public function mapProductImageSetStorageTransferToRestAttributesTransfer(
        ProductImageSetStorageTransfer $productImageSetStorageTransfer,
        RestConfigurableBundleTemplateImageSetsAttributesTransfer $restConfigurableBundleTemplateImageSetsAttributesTransfer
    ): RestConfigurableBundleTemplateImageSetsAttributesTransfer;
}
