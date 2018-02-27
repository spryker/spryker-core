<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class GiftCardDecisionRuleChecker implements GiftCardDecisionRuleCheckerInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardDecisionRulePluginInterface[]
     */
    protected $giftCardDecisionRulePlugins;

    /**
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardDecisionRulePluginInterface[] $giftCardDecisionRulePlugins
     */
    public function __construct(array $giftCardDecisionRulePlugins)
    {
        $this->giftCardDecisionRulePlugins = $giftCardDecisionRulePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(GiftCardTransfer $giftCardTransfer, QuoteTransfer $quoteTransfer)
    {
        foreach ($this->giftCardDecisionRulePlugins as $giftCardDecisionRulePlugin) {
            if (!$giftCardDecisionRulePlugin->isApplicable($giftCardTransfer, $quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
