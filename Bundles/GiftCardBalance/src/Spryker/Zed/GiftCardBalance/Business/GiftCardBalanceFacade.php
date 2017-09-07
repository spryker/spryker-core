<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business;

use Generated\Shared\Transfer\GiftCardTransfer;

/**
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceBusinessFactory getFactory()
 */
class GiftCardBalanceFacade implements GiftCardBalanceFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function hasPositiveBalance(GiftCardTransfer $giftCardTransfer)
    {
        return $this->getFactory()->createGiftCardBalanceChecker()->hasPositiveBalance($giftCardTransfer);
    }

}
