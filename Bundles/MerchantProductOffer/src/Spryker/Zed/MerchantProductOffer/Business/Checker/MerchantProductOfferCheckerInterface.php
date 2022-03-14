<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Checker;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface MerchantProductOfferCheckerInterface
{
 /**
  * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
  *
  * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
  */
    public function check(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer;
}
