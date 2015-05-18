<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Zed\Checkout\Business\Model\Workflow\Context;

/**
 * Class PrepareBillingAddress
 * @package SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task
 */
class PrepareBillingAddress extends AbstractPrepareAddress
{
    /**
     * @param Order   $transferOrder
     * @param Context $context
     * @param array   $logContext
     */
    public function __invoke(OrderTransfer $transferOrder, Context $context, array $logContext)
    {
        if ($transferOrder->getCustomer()) {
            $transferCustomerAddress = $this->loadCustomerAddress($transferOrder->getBillingAddress(), $transferOrder->getCustomer());

            if (!$transferOrder->getCustomer()->getAddresses()) {
                $transferCustomerAddress->setIsDefaultBilling(true);
                $transferCustomerAddress->setIsDefaultShipping(true);
                $transferOrder->getCustomer()->setShippingAddress($transferCustomerAddress);
                $transferOrder->getCustomer()->setBillingAddress($transferCustomerAddress);
            }

            if ($transferCustomerAddress->getIdCustomerAddress()) {
                if ($this->facadeCustomer->createFacadeAddress()->checkIsModified($transferCustomerAddress)) {
                    $transferCustomerAddress->setIdCustomerAddress(null);
                    $this->facadeCustomer->createFacadeAddress()->addAddress($transferCustomerAddress);
                }
                $transferOrder->getCustomer()->setBillingAddress($transferCustomerAddress);
            } else {
                $this->facadeCustomer->createFacadeAddress()->addAddress($transferCustomerAddress);
            }
            $transferOrder->getCustomer()->getAddresses()->add($transferCustomerAddress);
        }
    }
}
