<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferPostCreatePluginInterface
{
    /**
     * Specification:
     * - This plugin is executed after a ProductOffer creation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
