<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Discount\DiscountInterface;
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
     * @param DiscountInterface $discountTransfer
     * @param CalculableInterface $container
     *
     * @return ModelResult
     */
    public function check(DiscountInterface $discountTransfer, CalculableInterface $container)
    {
        $componentResult = new ModelResult();

        if (count($container->getCalculableObject()->getCouponCodes()) < 1) {
            $componentResult->addError('Voucher not set.');
            return $componentResult;
        }

        $errors = [];
        $result = true;

        foreach ($container->getCalculableObject()->getCouponCodes() as $code) {
            $idDiscountVoucherPool = $this->getContext()[self::KEY_DATA];
            $response = $this
                ->getDependencyContainer()
                ->getDiscountFacade()
                ->isVoucherUsable($code, $idDiscountVoucherPool)
            ;

            $result &= $response->isSuccess();
            if ($response->isSuccess()) {
                $discountTransfer->addUsedCode($code);
            }
            $errors = array_merge($errors, $response->getErrors());
        }

        $componentResult->addErrors($errors);

        return $componentResult;
    }

}
