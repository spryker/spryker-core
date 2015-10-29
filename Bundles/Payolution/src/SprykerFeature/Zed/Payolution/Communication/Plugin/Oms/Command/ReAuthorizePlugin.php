<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Communication\Plugin\Oms\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method PayolutionFacade getFacade()
 */
class ReAuthorizePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        /** @var SpyPaymentPayolution $paymentEntity */
        $paymentEntity = $orderEntity->getSpyPaymentPayolutions()->getFirst();
        $this->getFacade()->reAuthorizePayment($paymentEntity->getIdPaymentPayolution());

        return [];
    }

}
