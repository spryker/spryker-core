<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

abstract class AbstractCommand
{

    /**
     * @param $message
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param bool $isSuccess
     */
    protected function addNote($message, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, $isSuccess = true)
    {
        $this->facadeSales->addNote($message, $orderEntity, $isSuccess, get_class($this));
    }

}
