<?php

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

abstract class AbstractCommand extends AbstractPlugin
{

    /**
     * @param $message
     * @param SpySalesOrder $orderEntity
     * @param bool $isSuccess
     */
    protected function addNote($message, SpySalesOrder $orderEntity, $isSuccess = true)
    {
        $this->facadeSales->addNote($message, $orderEntity, $isSuccess, get_class($this));
    }
}
