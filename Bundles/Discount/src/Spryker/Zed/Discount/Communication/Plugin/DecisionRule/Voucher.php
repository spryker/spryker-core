<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Business\ModelResult;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
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
            $voucherValidation = $this->getFacade()->isVoucherUsable($voucherCode);

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
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
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
