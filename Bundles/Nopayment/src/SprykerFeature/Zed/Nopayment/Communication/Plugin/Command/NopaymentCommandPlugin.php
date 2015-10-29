<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Nopayment\Communication\Plugin\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerFeature\Zed\Nopayment\Communication\NopaymentDependencyContainer;

/**
 * @method NopaymentDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->createFacade()->setAsPaid($orderItems);
    }

}
