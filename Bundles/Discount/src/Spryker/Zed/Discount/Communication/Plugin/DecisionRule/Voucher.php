<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Business\ModelResult;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;

/**
 * @method DiscountCommunicationFactory getFactory()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountTransfer $discountTransfer
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ModelResult
     */
    public function check(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer)
    {
        $voucherCodeValidationResults = new ModelResult();

        if (!$this->isVoucherCodesProvided($quoteTransfer)) {
            $voucherCodeValidationResults->setSuccess(false);

            return $voucherCodeValidationResults;
        }

        $voucherValidation = $this->getFactory()
            ->getDiscountFacade()
            ->isVoucherUsable($discountTransfer->getVoucherCode());

        $voucherCodeValidationResults->addErrors($voucherValidation->getErrors());

        return $voucherCodeValidationResults;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
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
