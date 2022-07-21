<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\PersistentCart;

use Generated\Shared\Transfer\CartChangeTransfer;

interface BundleItemReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function replaceBundlesWithUnitedItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
