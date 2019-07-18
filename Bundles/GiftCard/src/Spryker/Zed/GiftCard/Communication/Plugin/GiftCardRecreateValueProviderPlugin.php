<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;

class GiftCardRecreateValueProviderPlugin implements GiftCardValueProviderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return int
     */
    public function getValue(GiftCardTransfer $giftCardTransfer)
    {
        return $giftCardTransfer->getValue();
    }
}
