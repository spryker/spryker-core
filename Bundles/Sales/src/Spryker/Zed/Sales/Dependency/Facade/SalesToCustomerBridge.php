<?php


namespace Spryker\Zed\Sales\Dependency\Facade;


use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

class SalesToCustomerBridge implements SalesToCustomerInterface
{
    /**
     * @var CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param CustomerFacadeInterface $customerFacade
     */
    public function __construct($customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerByReference($customerReference)
    {
        return $this->customerFacade->findCustomerByReference($customerReference);
    }
}