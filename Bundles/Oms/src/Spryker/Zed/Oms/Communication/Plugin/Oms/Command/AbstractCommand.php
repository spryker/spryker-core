<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Command;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method \Spryker\Zed\Oms\Business\OmsFacade getFacade()
 * @method \Spryker\Zed\Oms\Communication\OmsCommunicationFactory getFactory()
 */
abstract class AbstractCommand extends AbstractPlugin
{

    /**
     * //TODO: make addNote work again some time in the future
     *
     * @param string $message
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param bool $isSuccess
     *
     * @return void
     */
    protected function addNote($message, SpySalesOrder $orderEntity, $isSuccess = true)
    {
        $this->facadeSales->addNote($message, $orderEntity, $isSuccess, get_class($this));
    }

}
