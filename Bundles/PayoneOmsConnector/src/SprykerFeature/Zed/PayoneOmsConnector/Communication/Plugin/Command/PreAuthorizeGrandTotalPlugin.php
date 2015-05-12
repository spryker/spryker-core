<?php

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use Generated\Shared\Transfer\PayoneAuthorizationTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class PreAuthorizeGrandTotalPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        //FIXME Pseudo Code Example
        $transferAuthorization = new PayoneAuthorizationTransfer();
        $transferAuthorization->setAmount($orderEntity->getGrandTotal());
        $transferAuthorization->setReferenceId($orderEntity->getIncrementId());

        $paymentUserDataTransfer = $data['???_SOME_KEY_TO_GET_FORM_DATA_???'];
        $transferAuthorization->setPaymentUserData($paymentUserDataTransfer);

        $this->getDependencyContainer()->createPayoneFacade()->preAuthorize($transferAuthorization);
    }

}
