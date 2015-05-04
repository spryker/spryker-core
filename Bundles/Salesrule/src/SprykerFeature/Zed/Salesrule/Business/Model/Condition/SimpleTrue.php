<?php
namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use Generated\Shared\Transfer\SalesOrderTransfer;

/**
 * This class is used for unit tests only
 */
final class SimpleTrue extends AbstractCondition
{
    /**
     * @param Order $order
     * @return bool
     */
    public function match(Order $order)
    {
        return true;
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
    }
}
