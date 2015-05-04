<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

use Generated\Shared\Transfer\CalculationDiscountTransfer;
use Generated\Shared\Transfer\SalesOrderTransfer;
use Generated\Shared\Transfer\SalesruleItemTransfer;
use SprykerFeature\Zed\Library\Copy;
use SprykerFeature\Zed\Salesrule\Business\Model\Action\AbstractAction;

class Salesrule
{

    const ID_SALES_RULE_URL_PARAMETER = 'id-sales-rule';

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\Order $order
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return ConditionStack
     */
    public function getConditionStack(Order $order, \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        return $this->factory->createModelConditionStack($order)->initFromSalesrule($salesrule);
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return bool
     */
    public function isCouponDiscount(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        $conditions = $salesrule->getSalesruleConditions();
        foreach ($conditions as $condition) {
            if ($condition->getCondition() === Code::COUPON_CODE_CONDITION) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param DiscountableItemInterface $item
     * @param DiscountCollection|Discount[] $discounts
     */
    public function addDiscountsToDiscountableItem(DiscountableItemInterface $item, DiscountCollection $discounts)
    {
        foreach ($discounts as $discount) {
            $salesRule = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()->findPk($discount->getSalesruleId());
            if ($discount->getAmount() <= 0 || !$salesRule) {
                continue;
            }
            $discountEntity = $this->createDiscountEntity($salesRule, $discount);
            $item->addDiscount($discountEntity);
        }
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesRule
     * @param \SprykerFeature\Shared\Calculation\Transfer\Discount $discount
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount
     */
    protected function createDiscountEntity(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesRule, Discount $discount)
    {
        $discountEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount();
        $discountEntity->setName($salesRule->getName());
        $discountEntity->setDescription($salesRule->getDescription());
        $discountEntity->setDisplayName($salesRule->getDisplayName());
        $discountEntity->setScope($salesRule->getScope());
        $discountEntity->setAction($salesRule->getAction());
        $discountEntity->setAmount($discount->getAmount());

        $conditions = [];
        foreach ($salesRule->getSalesruleConditions() as $condition) {
            $conditions[] = $condition->getCondition();
        }
        if (!empty($conditions)) {
            $discountEntity->setConditions(implode(',', $conditions));
        }

        if ($discount->getType() === AbstractAction::TYPE_COUPON_DISCOUNT) {
            $salesRuleCodes = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodeQuery::create()
                ->filterByCode($discount->getUsedCodes())
                ->find();
            foreach ($salesRuleCodes as $salesRuleCode) {
                $discountCodeEntity = $this->createDiscountCodeEntity($salesRuleCode, $discountEntity);
                $discountEntity->addDiscountCode($discountCodeEntity);
            }
        }
        return $discountEntity;
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode $salesRuleCode
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscountCode
     */
    protected function createDiscountCodeEntity(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode $salesRuleCode)
    {
        $discountCodeEntity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscountCode();
        $discountCodeEntity->setCode($salesRuleCode->getCode());
        $discountCodeEntity->setCodepoolName($salesRuleCode->getCodepool()->getName());
        $discountCodeEntity->setIsReusable($salesRuleCode->getCodepool()->getIsReusable());
        $discountCodeEntity->setIsOncePerCustomer($salesRuleCode->getCodepool()->getIsOncePerCustomer());
        $discountCodeEntity->setIsRefundable($salesRuleCode->getCodepool()->getIsRefundable());
        return $discountCodeEntity;
    }

    /**
     * @param $orderId
     * @param $log
     */
    public function addSalesruleLog($orderId, $log)
    {
        $logEntity = new \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleLog();
        $logEntity->setFkSalesOrder($orderId);
        $logEntity->setLog($log);
        $logEntity->save();
    }

    /**
     * @param int $idSalesrule
     */
    public function deleteSalesrule($idSalesrule)
    {
        $salesruleEntity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()->findPk($idSalesrule);
        $conditions = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()->findByFkSalesrule($salesruleEntity->getPrimaryKey());

        \Propel\Runtime\Propel::getConnection()->beginTransaction();

        foreach ($conditions as $condition) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $condition */
            $condition->delete();
        }
        $salesruleEntity->delete();

        \Propel\Runtime\Propel::getConnection()->commit();
    }

    /**
     * @param Item $salesRuleItem
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule
     */
    public function saveSalesRule(Item $salesRuleItem)
    {
        $idSalesRule = $salesRuleItem->getIdSalesrule() ? : null;
        $salesRuleItem->setIdSalesrule($idSalesRule);

        $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()
            ->filterByIdSalesrule($idSalesRule)->findOneOrCreate();

        Copy::transferToEntityNoNullValues($salesRuleItem, $entity);
        $entity->save();
        return $entity;
    }
}
