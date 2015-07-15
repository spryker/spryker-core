<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use Generated\Shared\Transfer\RefundTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class RefundPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $refundTransfer = new RefundTransfer();

        $this->getDependencyContainer()->createPayoneFacade()->refund($refundTransfer);
    }

}
