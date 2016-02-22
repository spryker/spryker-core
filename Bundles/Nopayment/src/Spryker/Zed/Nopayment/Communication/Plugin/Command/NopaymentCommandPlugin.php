<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Command;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method \Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Nopayment\Business\NopaymentFacade getFacade()
 */
class NopaymentCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $order, ReadOnlyArrayObject $data)
    {
        return $this->getFacade()->setAsPaid($orderItems);
    }

}
