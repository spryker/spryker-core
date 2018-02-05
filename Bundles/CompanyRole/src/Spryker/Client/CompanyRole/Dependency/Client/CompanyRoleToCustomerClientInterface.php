<?php

namespace Spryker\Client\CompanyRole\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyRoleToCustomerClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer;
}