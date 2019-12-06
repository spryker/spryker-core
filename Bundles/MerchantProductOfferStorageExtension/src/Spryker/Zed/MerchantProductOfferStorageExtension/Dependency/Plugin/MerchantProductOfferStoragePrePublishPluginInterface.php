<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface MerchantProductOfferStoragePrePublishPluginInterface
{
    /**
     * Specification:
     * - This plugin is executed before product_offer_storage and product_concrete_offers_store publishing.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    public function isValid(ProductOfferTransfer $productOfferTransfer): bool;
}
