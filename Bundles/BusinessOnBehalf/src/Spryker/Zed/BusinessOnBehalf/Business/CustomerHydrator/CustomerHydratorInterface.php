<?php

namespace Spryker\Zed\BusinessOnBehalf\Business\CustomerHydrator;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerHydratorInterface
{
    public function hydrateCustomerWithCompanyUser(CustomerTransfer $customerTransfer): CustomerTransfer;
}
