<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface MerchantProductOfferPublishPreCheckPluginInterface
{
    /**
     * Specification:
     * - This plugin is executed before a ProductOffer of merchant is saved to storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    public function isValid(ProductOfferTransfer $productOfferTransfer): bool;
}
