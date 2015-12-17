<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Command;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory;

/**
 * @method NopaymentCommunicationFactory getFactory()
 */
class NopaymentCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param SpySalesOrder $order
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $order, ReadOnlyArrayObject $data)
    {
        return $this->getFactory()->createFacade()->setAsPaid($orderItems);
    }

}
