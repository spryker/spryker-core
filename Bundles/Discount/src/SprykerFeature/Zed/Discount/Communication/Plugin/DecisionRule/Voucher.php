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
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;

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

        $idDiscountVoucherPool = $this->getIdVoucherPool();

        foreach ($container->getCalculableObject()->getCouponCodes() as $code) {

            $response = $this
                ->getDependencyContainer()
                ->getDiscountFacade()
                ->isVoucherUsable($code, $idDiscountVoucherPool);

            $result &= $response->isSuccess();
            if ($response->isSuccess()) {
                $discountTransfer->addUsedCode($code);
            }
            $errors = array_merge($errors, $response->getErrors());
        }

        $componentResult->addErrors($errors);

        return $componentResult;
    }

    /**
     * @return int
     */
    protected function getIdVoucherPool()
    {
        if (array_key_exists(self::KEY_DATA, $this->getContext())) {
            return $this->getContext()[self::KEY_DATA]; //idDiscountVoucher
        }

        if (array_key_exists(self::KEY_ENTITY, $this->getContext())) {
            /* @var $decisionRuleEntity SpyDiscountDecisionRule */
            $decisionRuleEntity = $this->getContext()[self::KEY_ENTITY];
            return $decisionRuleEntity->getDiscount()->getFkDiscountVoucherPool();
        }
    }

}
