<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\QuoteItem;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteItemReaderInterface
{
  /**
   * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
   *
   * @return \Generated\Shared\Transfer\QuoteResponseTransfer
   */
    public function readItem(CartItemRequestTransfer $cartItemRequestTransfer): QuoteResponseTransfer;
}
