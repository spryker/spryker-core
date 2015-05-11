<?php

namespace SprykerFeature\Zed\Nopayment\Communication\Plugin\Condition;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;

class NopaymentConditionPlugin extends AbstractPlugin implements ConditionInterface
{

    public function check(SpySalesOrder $order)
    {

    }
}
