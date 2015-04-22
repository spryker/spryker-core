<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class Voucher extends AbstractDecisionRule implements
    DiscountDecisionRulePluginInterface
{
    /**
     * @param DiscountEntity $discountEntity
     * @param DiscountableContainerInterface $container
     * @param DecisionRuleEntity $decisionRuleEntity
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        DiscountableContainerInterface $container,
        DecisionRuleEntity $decisionRuleEntity = null
    ) {
        $componentResult = new ModelResult();

        if (count($container->getCouponCodes()) < 1) {
            return $componentResult;
        }
        $errors = [];
        $result = true;
        foreach ($container->getCouponCodes() as $code) {
            $idVoucherCodePool = $this->getContext()[self::KEY_DATA];
            $response = $this->getDependencyContainer()->getDiscountFacade()
                ->isVoucherUsable($code, $idVoucherCodePool);

            $result &= $response->isSuccess();
            $errors = array_merge($errors, $response->getErrors());
        }

        $componentResult->addErrors($errors);

        return $componentResult;
    }
}
