<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

abstract class AbstractCommand extends AbstractPlugin
{

    /**
     * //TODO: make addNote work again some time in the future
     *
     * @param string $message
     * @param SpySalesOrder $orderEntity
     * @param bool $isSuccess
     *
     * @return void
     */
    protected function addNote($message, SpySalesOrder $orderEntity, $isSuccess = true)
    {
        $this->facadeSales->addNote($message, $orderEntity, $isSuccess, get_class($this));
    }

}
