<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

/**
 * Provides ability to expand ProductOfferStorage transfer object.
 */
interface ProductOfferStorageExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns expanded ProductOfferStorage transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer;
}
