<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition;

class PaymentRedirected implements ConditionInterface
{

    public function check(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        $b = (microtime()*1000000%2)?true:false;
        $bS = $b?'true':'false';
        \SprykerFeature_Shared_Library_Log::log('Condtion PaymentRedirected for item: '.$orderItem->getIdSalesOrderItem().' '.$bS, 'statemachine.log');
        return $b;
    }

}
