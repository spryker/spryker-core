<?php
namespace SprykerFeature\Zed\Checkout\Business\Model\Workflow\Task;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Shared\Transfer\SalesAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

abstract class AbstractPrepareAddress extends AbstractTask
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }


    /**
     * @param SalesAddressTransfer $salesAddress
     * @param CustomerTransfer $transferCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerCustomerAddressTransfer
     */
    protected function loadCustomerAddress(SalesAddressTransfer $salesAddress, CustomerTransfer $transferCustomer)
    {
        $transferCustomerAddress = new \Generated\Shared\Transfer\CustomerCustomerAddressTransfer();
        $transferCustomerAddress->fromArray($salesAddress->toArray(), true);
        $transferCustomerAddress->setFkCustomer($transferCustomer->getIdCustomer());
        $transferCustomerAddress->setFkMiscCountry($this->getFkCountry($salesAddress));

        return $transferCustomerAddress;
    }

    /**
     * @param Address $transferAddress
     *
     * @return int
     */
    protected function getFkCountry($transferAddress)
    {
        if ($transferAddress->getFkMiscCountry()) {
            return $transferAddress->getFkMiscCountry();
        }

        return $this->locator->country()->facade()->getIdCountryByIso2Code($transferAddress->getIso2Country());
    }
}
