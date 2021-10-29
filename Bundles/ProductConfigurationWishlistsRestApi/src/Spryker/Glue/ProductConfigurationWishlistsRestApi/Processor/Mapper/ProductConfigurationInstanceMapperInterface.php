<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer;

interface ProductConfigurationInstanceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestWishlistItemProductConfigurationInstanceAttributesToProductConfigurationInstance(
        RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceToRestWishlistItemProductConfigurationInstanceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
    ): RestWishlistItemProductConfigurationInstanceAttributesTransfer;
}
