<?php

namespace Spryker\Zed\BusinessOnBehalf\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserCollectionFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): array;
}