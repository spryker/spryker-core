<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
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
            $voucherValidation = $this->getDependencyContainer()->getDiscountFacade()->isVoucherUsable($voucherCode);

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
