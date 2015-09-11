<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountEntity $discountEntity
     * @param CalculableInterface $container
     *
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        CalculableInterface $container
    ) {
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
            $errors = array_merge($errors, $response->getErrors());
        }

        $componentResult->addErrors($errors);

        return $componentResult;
    }

}
