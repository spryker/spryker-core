<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Plugin;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCardBalance\Communication\GiftCardBalanceCommunicationFactory getFactory()
 */
class BalanceCheckerApplicabilityPlugin extends AbstractPlugin implements GiftCardDecisionRulePluginInterface
{
    /**
     * Specification:
     * - This decision rule is a part of gift card balance log strategy
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(GiftCardTransfer $giftCardTransfer, QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->hasPositiveBalance($giftCardTransfer);
    }
}
