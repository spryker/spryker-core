<?php

namespace Spryker\Client\CompanyRole\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\CustomerClient;
use Spryker\Client\Customer\CustomerClientInterface;

class CompanyRoleToCustomerClientBridge implements CompanyRoleToCustomerClientInterface
{
    /**
     * @var CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer
    {
        return $this->customerClient->getCustomer();
    }
}