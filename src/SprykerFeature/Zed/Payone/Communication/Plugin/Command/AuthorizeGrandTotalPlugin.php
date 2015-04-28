<?php

namespace SprykerFeature\Zed\Payone\Communication\Command;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class AuthorizeGrandTotalPlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $orderItems
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     * @return array $returnArray
     */
    public function run(array $orderItems, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {

        //FIXME Pseudo Code

        $transferAuthorization = $this->getLocator()->payone()->transferAuthorization();
        $transferAuthorization->setAmount($orderEntity->getGrandTotal());
        $transferAuthorization->setReferenceId($orderEntity->getIncrementId());
        $transferAuthorization->setPaymentFormData($data['???_SOME_KEY_TO_GET_FORM_DATA_???']);

        $this->getDependencyContainer()->createPayoneFacade()->authorize($transferAuthorization);
    }

}
