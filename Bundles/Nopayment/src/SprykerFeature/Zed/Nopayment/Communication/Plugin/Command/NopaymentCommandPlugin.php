<?php

namespace SprykerFeature\Zed\Nopayment\Communication\Plugin\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Nopayment\Communication\NopaymentDependencyContainer;

/**
 * @method NopaymentDependencyContainer getDependencyContainer()
 */
class NopaymentCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param SpySalesOrderItem[] $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        return $this->getDependencyContainer()->createFacade()->setAsPaid($orderEntity, $orderItems);
    }
}