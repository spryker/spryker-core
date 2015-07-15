<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use Generated\Shared\Transfer\AuthorizationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PreAuthorizePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $payoneFacade = $this->getDependencyContainer()->createPayoneFacade();

        $orderTransfer = new OrderTransfer();
        $orderTransfer->fromArray($orderEntity->toArray());

        $paymentTransfer = $payoneFacade->getPayment($orderTransfer);

        $authorizationTransfer = new AuthorizationTransfer();
        $authorizationTransfer->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $authorizationTransfer->setAmount($orderEntity->getGrandTotal());
        $authorizationTransfer->setReferenceId($orderEntity->getOrderreference());
        $authorizationTransfer->setOrder($orderTransfer);

        $this->getDependencyContainer()->createPayoneFacade()->preAuthorize($authorizationTransfer);
    }

}
