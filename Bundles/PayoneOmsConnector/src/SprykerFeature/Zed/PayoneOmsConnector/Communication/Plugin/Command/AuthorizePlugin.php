<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\PayoneOmsConnector\Communication\PayoneOmsConnectorDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method PayoneOmsConnectorDependencyContainer getDependencyContainer()
 */
class AuthorizePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return array $returnArray
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $paymentEntity = $orderEntity->getSpyPaymentPayone();
        $this->getDependencyContainer()->createPayoneFacade()->authorizePayment($paymentEntity->getIdSalesOrder());
        
        return [];
    }

}
