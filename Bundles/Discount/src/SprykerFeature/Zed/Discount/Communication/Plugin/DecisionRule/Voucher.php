<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Voucher extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountEntity $discountEntity
     *
     * @param CalculableInterface $container
     * @param DecisionRuleEntity $decisionRuleEntity
     *
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        CalculableInterface $container,
        DecisionRuleEntity $decisionRuleEntity = null
    ) {
        $componentResult = new ModelResult();

        if (count($container->getCalculableObject()->getCouponCodes()) < 1) {
            $componentResult->addError('Voucher not set.');
            return $componentResult;
        }

        $errors = [];
        $result = true;

        foreach ($container->getCalculableObject()->getCouponCodes() as $code) {
            $idVoucherCodePool = $this->getContext()[self::KEY_DATA];
            $response = $this
                ->getDependencyContainer()
                ->getDiscountFacade()
                ->isVoucherUsable($code, $idVoucherCodePool)
            ;

            $result &= $response->isSuccess();
            $errors = array_merge($errors, $response->getErrors());
        }

        $componentResult->addErrors($errors);

        return $componentResult;
    }

}
