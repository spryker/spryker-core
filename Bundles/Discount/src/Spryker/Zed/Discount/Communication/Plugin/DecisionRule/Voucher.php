<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Business\ModelResult;
use Spryker\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getCommunicationFactory()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountTransfer $discountTransfer
     * @param CalculableInterface $container
     *
     * @return ModelResult
     */
    public function check(DiscountTransfer $discountTransfer, CalculableInterface $container)
    {
        $voucherCodeValidationResults = new ModelResult();

        if (!$this->isVoucherCodesProvided($container)) {
            $voucherCodeValidationResults->setSuccess(false);

            return $voucherCodeValidationResults;
        }

        $validationErrors = [];
        $validVoucherCodes = [];
        foreach ($discountTransfer->getUsedCodes() as $voucherCode) {
            $voucherValidation = $this->getCommunicationFactory()->getDiscountFacade()->isVoucherUsable($voucherCode);

            if ($voucherValidation->isSuccess()) {
                $validVoucherCodes[] = $voucherCode;
            }
            $validationErrors = array_merge($validationErrors, $voucherValidation->getErrors());
        }

        $discountTransfer->setUsedCodes($validVoucherCodes);
        $voucherCodeValidationResults->addErrors($validationErrors);

        return $voucherCodeValidationResults;
    }

    /**
     * @param CalculableInterface $container
     *
     * @return bool
     */
    protected function isVoucherCodesProvided(CalculableInterface $container)
    {
        if (count($container->getCalculableObject()->getCouponCodes()) < 1) {
            return false;
        }

        return true;
    }

}
