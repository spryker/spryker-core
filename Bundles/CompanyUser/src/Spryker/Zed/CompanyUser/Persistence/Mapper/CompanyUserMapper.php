<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SpyCompanyUserEntityTransfer;

class CompanyUserMapper implements CompanyUserMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer
     */
    public function mapCompanyUserTransferToEntityTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): SpyCompanyUserEntityTransfer {
        $companyUserEntityTransfer = new SpyCompanyUserEntityTransfer();
        $data = $companyUserTransfer->toArray();
        unset($data['customer']);
        $companyUserEntityTransfer->fromArray($data, true);

        return $companyUserEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer $companyUserEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function mapEntityTransferToCompanyUserTransfer(
        SpyCompanyUserEntityTransfer $companyUserEntityTransfer
    ): CompanyUserTransfer {
        $data = $companyUserEntityTransfer->toArray();
        $customerData = $data['customer'];
        unset($data['customer'], $data['spy_company_role_to_company_users']);
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->fromArray($data, true);

        if ($customerData !== null) {
            $customerTransfer = new CustomerTransfer();
            $customerTransfer->setIdCustomer($customerData['id_customer']);
            $customerTransfer->setSalutation($customerData['salutation']);
            $customerTransfer->setFirstName($customerData['first_name']);
            $customerTransfer->setLastName($customerData['last_name']);
            $customerTransfer->setEmail($customerData['email']);

            $companyUserTransfer->setCustomer($customerTransfer);
        }

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUserEntityTransfer[] $collection
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function mapCompanyUserCollection($collection): CompanyUserCollectionTransfer
    {
        $companyUsers = new ArrayObject();
        $companyUserCollectionTransfer = new CompanyUserCollectionTransfer();

        foreach ($collection as $companyUserEntityTransfer) {
            $companyUsers->append($this->mapEntityTransferToCompanyUserTransfer($companyUserEntityTransfer));
        }

        $companyUserCollectionTransfer->setCompanyUsers($companyUsers);

        return $companyUserCollectionTransfer;
    }
}
