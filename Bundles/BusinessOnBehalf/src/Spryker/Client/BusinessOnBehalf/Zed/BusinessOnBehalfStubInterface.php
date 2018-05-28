<?php

namespace Spryker\Client\BusinessOnBehalf\Zed;

use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): array;
}