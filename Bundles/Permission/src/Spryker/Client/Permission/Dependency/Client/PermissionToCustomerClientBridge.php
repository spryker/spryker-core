<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission\Dependency\Client;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;

class PermissionToCustomerClientBridge implements PermissionToCustomerClientInterface
{
    /** @var \Spryker\Client\Customer\CustomerClientInterface */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     */
    public function __construct($customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @deprecated The concept of the getting information from the session will be changed.
     * Please avoid to use this method directly.
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function getCompanyUser()
    {
//        return $this->customerClient->getCustomer();
        $companyRole = (new CompanyRoleTransfer())
            ->setName('manager')
            ->setIdCompanyRole(1)
            ->setIsDefault(0)
            ->setPermissionCollection(new PermissionCollectionTransfer());

        $companyRoleCollection = (new CompanyRoleCollectionTransfer())
            ->addRole($companyRole);

        $companyUser = new CompanyUserTransfer();
        $companyUser->setIdCompanyUser(1);
        $companyUser->setCompanyRoleCollection($companyRoleCollection);

        return $companyUser;
    }
}
