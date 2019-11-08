<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface MerchantProductOfferStorageExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands ProductOfferStorageTransfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    public function expand(ProductOfferStorageTransfer $productOfferStorageTransfer): ProductOfferStorageTransfer;
}
