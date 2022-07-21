<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided ProductOfferTransfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expand(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;
}
