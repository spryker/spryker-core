<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Business\ModelResult;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function check(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        $voucherCodeValidationResults = new ModelResult();

        if (!$this->isVoucherCodesProvided($quoteTransfer)) {
            $voucherCodeValidationResults->setSuccess(false);

            return $voucherCodeValidationResults;
        }

        $voucherValidation = $this->getFacade()->isVoucherUsable($discountTransfer->getVoucherCode());

        $voucherCodeValidationResults->addErrors($voucherValidation->getErrors());

        return $voucherCodeValidationResults;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isVoucherCodesProvided(QuoteTransfer $quoteTransfer)
    {
        if (count($quoteTransfer->getVoucherDiscounts()) < 1) {
            return false;
        }

        return true;
    }

}
