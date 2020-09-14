<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Replacer;

use Generated\Shared\Transfer\CartItemReplaceTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface CartItemReplacerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemReplaceTransfer $cartItemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function replaceItem(CartItemReplaceTransfer $cartItemReplaceTransfer): QuoteResponseTransfer;
}
