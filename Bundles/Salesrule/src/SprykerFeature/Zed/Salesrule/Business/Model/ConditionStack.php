<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

use Generated\Shared\Transfer\SalesOrderTransfer;
use SprykerFeature\Zed\Salesrule\Business\Model\Condition\AbstractCondition;

class ConditionStack extends \ArrayObject
{

    /**
     * @var Order
     */
    protected $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return bool
     */
    public function canExecuteAction()
    {
        /* @var AbstractCondition $condition */
        foreach ($this as $condition) {
            if (false === $condition->match($this->order)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule
     * @return $this
     */
    public function initFromSalesrule(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule $salesrule)
    {
        foreach ($salesrule->getSalesruleConditions() as $condition) {
            $configuration = (array) json_decode($condition->getConfiguration(), true);
            $conditionFacadeGetter = 'createModel' . $condition->getCondition();

            /* @var AbstractCondition $conditionModel */
            $conditionModel = $this->factory->$conditionFacadeGetter();
            $conditionModel->setConfiguration($configuration);
            $this->append($conditionModel);
        }

        return $this;
    }
}
