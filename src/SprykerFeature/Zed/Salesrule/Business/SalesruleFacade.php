<?php

namespace SprykerFeature\Zed\Salesrule\Business;

use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Salesrule\Transfer\CodePool;
use SprykerFeature\Shared\Salesrule\Transfer\Item;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Salesrule\Business\Model\ConditionStack;
use SprykerFeature\Zed\Salesrule\Business\Model\DiscountableItemInterface;

class SalesruleFacade extends AbstractFacade
{

    public function createSettings()
    {
        return $this->factory->createSettings();
    }

    /**
     * @return Calculator\SalesruleCalculator
     */
    public function createSalesruleCalculator()
    {
        return $this->factory->createCalculatorSalesruleCalculator($this->factory, $this);
    }

    /**
     * @param string $type
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule[]
     */
    public function getActiveSalesrules($type = null)
    {
        return $this->factory->createModelFinder()->getActiveSalesrules($type);
    }

    /**
     * @param Order $order
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return ConditionStack
     */
    public function getConditionStack(Order $order, \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        return $this->factory->createModelSalesrule()->getConditionStack($order, $salesrule);
    }

    /**
     * @param string $code
     * @return bool
     */
    public function canUseCouponCode($code)
    {
        return $this->factory->createModelCodeUsage()->canUseCouponCode($code);
    }

    /**
     * @param string $code
     * @return bool
     */
    public function canRefundCouponCode($code)
    {
        return $this->factory->createModelFinder()->canRefundCouponCode($code); // TODO
    }

    /**
     * @param int $orderId
     * @return int
     */
    public function getCodeUsageCountForOrder($orderId)
    {
        return $this->factory->createModelCodeUsage()->getCodeUsageCountForOrder($orderId);
    }

    /**
     * @param DiscountableItemInterface $item
     * @param DiscountCollection $transferItem
     */
    public function addDiscountsToDiscountableItem(DiscountableItemInterface $item, DiscountCollection $transferItem)
    {
        $this->factory->createModelSalesrule()->addDiscountsToDiscountableItem($item, $transferItem);
    }

    /**
     * @param $idSalesOrder
     * @param array $codes
     * @param \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer
     * @return bool
     */
    public function addCodeUsage($idSalesOrder, array $codes, \SprykerFeature\Zed\Customer\Persistence\Propel\PacCustomer $customer = null)
    {
        $this->factory->createModelCodeUsage()->addCodeUsage($idSalesOrder, $codes, $customer);
    }

    /**
     * @param int $orderId
     * @param $log
     */
    public function addSalesruleLog($orderId, $log)
    {
        $this->factory->createModelSalesrule()->addSalesruleLog($orderId, $log);
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return bool
     */
    public function isCouponDiscount(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        return $this->factory->createModelSalesrule()->isCouponDiscount($salesrule);
    }

    /**
     * @return array
     */
    public function getSalesruleActions()
    {
        return $this->factory->createSettings()->getAvailableActions();
    }

    /**
     * @param $idSalesOrder
     * @return array
     */
    public function purgeSalesruleCodeUsage($idSalesOrder)
    {
        return $this->factory->createModelCodeUsage()->purgeSalesruleCodeUsage($idSalesOrder);
    }

    /**
     * @param int $idSalesrule
     */
    public function deleteSalesrule($idSalesrule)
    {
        $this->factory->createModelSalesrule()->deleteSalesrule($idSalesrule);
    }

    /**
     * @return array
     */
    public function getAvailableConditions()
    {
        return $this->factory->createModelCondition()->getAvailableConditions();
    }

    /**
     * @param string $name
     * @return \Zend_Form
     */
    public function getConditionFormByName($name)
    {
        return $this->factory->createModelCondition()->getConditionFormByName($name);
    }

    /**
     * @param int $codepoolId
     * @param int $amount
     * @param int $length
     * @param string $prefix
     * @param int $customerId
     * @param bool $isActive
     * @return bool
     */
    public function createCodes($codepoolId, $amount, $length = 6, $prefix = null, $customerId = null, $isActive = true)
    {
        return $this->factory->createModelCodepool()->createCodes($codepoolId, $amount, $length, $prefix, $customerId, $isActive);
    }

    /**
     * @param int $codepoolId
     * @param string $code
     * @param int $length
     * @param string $prefix
     * @param int $customerId
     * @param bool $isActive
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode
     */
    public function createCode($codepoolId, $code = null, $length = 6, $prefix = null, $customerId = null, $isActive = true)
    {
        return $this->factory->createModelCodepool()->createCode($codepoolId, $code, $length, $prefix, $customerId, $isActive);
    }

    /**
     * @param int $idSalesruleCode
     */
    public function deleteCode($idSalesruleCode)
    {
        $this->factory->createModelCode()->deleteCode($idSalesruleCode);
    }

    /**
     * @param int $idSalesRuleCondition
     */
    public function deleteSalesRuleCondition($idSalesRuleCondition)
    {
        $this->factory->createModelCondition()->deleteSalesRuleCondition($idSalesRuleCondition);
    }

    /**
     * @param int $idSalesruleCode
     * @return bool
     */
    public function canDeleteSalesruleCode($idSalesruleCode)
    {
        return $this->factory->createModelCode()->canDeleteSalesruleCode($idSalesruleCode);
    }

    /**
     * @param string $code
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCode
     */
    public function getSalesruleCodeByCode($code)
    {
        return $this->factory->createModelCodepool()->getSalesruleCodeByCode($code);
    }

    /**
     * @param int $codepoolId
     * @return string
     */
    public function getSalesruleCodePrefixByCodepoolId($codepoolId)
    {
        return $this->factory->createModelCodepool()->getSalesruleCodePrefixByCodepoolId($codepoolId);
    }

    /**
     * @param int $codepoolId
     * @return bool
     */
    public function canDeleteSalesruleCodepool($codepoolId)
    {
        return $this->factory->createModelCodepool()->canDeleteSalesruleCodepool($codepoolId);
    }

    /**
     * @param $codePoolId
     * @return mixed
     */
    public function deleteCodepool($codePoolId)
    {
        return $this->factory->createModelCodepool()->deleteCodepool($codePoolId);
    }

    /**
     * @param $prefix
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool
     */
    public function getSalesruleByPrefix($prefix)
    {
        return $this->factory->createModelCodepool()->getSalesruleByPrefix($prefix);
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $data
     * @return array
     */
    public function getFormDataFromConfiguration(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $data)
    {
        return $this->factory->createModelCondition()->getFormDataFromConfiguration($data);
    }

    /**
     * @param Item $salesRuleItem
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule
     */
    public function saveSalesRule(Item $salesRuleItem)
    {
        return $this->factory->createModelSalesrule()->saveSalesRule($salesRuleItem);
    }

    /**
     * @param array $conditionFormData
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition
     */
    public function saveCondition(array $conditionFormData)
    {
        return $this->factory->createModelCondition()->saveCondition($conditionFormData);
    }

    /**
     * @param CodePool $codePool
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool
     */
    public function saveCodePool(CodePool $codePool)
    {
        return $this->factory->createModelCodepool()->saveCodePool($codePool);
    }

    /**
     * @param $fkSalesRuleCodePool
     */
    public function downloadCodesFromCodePool($fkSalesRuleCodePool)
    {
        return $this->factory->createModelCode()->downloadCodesFromCodePool($fkSalesRuleCodePool);
    }
}
