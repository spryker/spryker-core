<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductTypeHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    public function isProductBundleByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    public function isGiftCardByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): bool;
}
