<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class PrepareShippingAddress
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class PrepareShippingAddress extends AbstractPrepareAddress
{
    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext)
    {
        // Check if both addresses contain the same values
        if ($transferOrder->getBillingAddress() == $transferOrder->getShippingAddress()) {
            return;
        }

        if ($transferOrder->getCustomer()) {
            $transferCustomerAddress = $this->loadCustomerAddress($transferOrder->getShippingAddress(), $transferOrder->getCustomer());

            if ($transferOrder->getCustomer()->getBillingAddress()->getIsDefaultShipping()) {
                $transferOrder->getCustomer()->getBillingAddress()->setIsDefaultShipping(false);
                $transferCustomerAddress->setIsDefaultShipping(true);
                $transferOrder->getCustomer()->setShippingAddress($transferCustomerAddress);
            }

            if ($transferCustomerAddress->getIdCustomerAddress()) {
                if ($this->facadeCustomer->createFacadeAddress()->checkIsModified($transferCustomerAddress)) {
                    $transferCustomerAddress->setIdCustomerAddress(null);
                    $this->facadeCustomer->createFacadeAddress()->addAddress($transferCustomerAddress);
                }
                $transferOrder->getCustomer()->setShippingAddress($transferCustomerAddress);
            } else {
                $this->facadeCustomer->createFacadeAddress()->addAddress($transferCustomerAddress);
            }
            $transferOrder->getCustomer()->getAddresses()->add($transferCustomerAddress);
        }
    }
}
