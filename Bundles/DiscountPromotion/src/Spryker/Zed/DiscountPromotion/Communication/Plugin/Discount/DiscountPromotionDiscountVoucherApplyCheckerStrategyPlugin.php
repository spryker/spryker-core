<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Discount;

use Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountVoucherApplyCheckerStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 */
class DiscountPromotionDiscountVoucherApplyCheckerStrategyPlugin extends AbstractPlugin implements DiscountVoucherApplyCheckerStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.promotionItems` to be provided.
     * - Requires `QuoteTransfer.promotionItems.discount` to be set.
     * - Checks if the voucher code is promotional.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer, string $voucherCode): bool
    {
        foreach ($quoteTransfer->getPromotionItems() as $promotionItemTransfer) {
            if ($promotionItemTransfer->getDiscountOrFail()->getVoucherCode() === $voucherCode) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * - Checks if the voucher code is added to the collection of used non-applied codes.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `true` with a success message, if the voucher code is added.
     * - Checks if the voucher code is added to the collection of voucher discounts otherwise.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `true` with a success message, if the voucher code is added.
     * - Returns `DiscountVoucherCheckResponseTransfer::isSuccessful` equal to `false` with an error message otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $voucherCode
     *
     * @return \Generated\Shared\Transfer\DiscountVoucherCheckResponseTransfer
     */
    public function check(QuoteTransfer $quoteTransfer, string $voucherCode): DiscountVoucherCheckResponseTransfer
    {
        return $this->getFacade()->checkVoucherCodeApplied($quoteTransfer, $voucherCode);
    }
}
