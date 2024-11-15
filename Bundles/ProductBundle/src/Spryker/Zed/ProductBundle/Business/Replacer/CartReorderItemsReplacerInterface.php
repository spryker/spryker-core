<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\Replacer;

use Generated\Shared\Transfer\CartReorderTransfer;

interface CartReorderItemsReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function replaceCartReorderItems(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
