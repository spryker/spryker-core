<?php

namespace Spryker\Zed\BusinessOnBehalf\Business\CustomerHydrator;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfRepositoryInterface;

class CustomerHydrator implements CustomerHydratorInterface
{
    protected $businessOnBehalfRepository;

    public function __construct(BusinessOnBehalfRepositoryInterface $businessOnBehalfRepository)
    {
        $this->businessOnBehalfRepository = $businessOnBehalfRepository;
    }

    public function hydrateCustomerWithCompanyUser(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if (!$customerTransfer->getCompanyUserTransfer()) {
            return $customerTransfer;
        }
        $defaultCompanyUser = $this->businessOnBehalfRepository
            ->findDefaultCompanyUserByCustomerId($customerTransfer->getIdCustomer());
        $customerTransfer->setCompanyUserTransfer($defaultCompanyUser);

        return $customerTransfer;
    }
}