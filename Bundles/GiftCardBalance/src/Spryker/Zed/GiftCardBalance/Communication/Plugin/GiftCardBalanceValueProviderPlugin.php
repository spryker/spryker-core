<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Plugin;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCardBalance\Communication\GiftCardBalanceCommunicationFactory getFactory()
 */
class GiftCardBalanceValueProviderPlugin extends AbstractPlugin implements GiftCardValueProviderPluginInterface
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
        return $this->getFacade()->getRemainingValue($giftCardTransfer);
    }
}
