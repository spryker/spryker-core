<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\BusinessOnBehalf\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface;

class BusinessOnBehalfStub implements BusinessOnBehalfStubInterface
{
    /**
     * @var \Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\BusinessOnBehalf\Dependency\Client\BusinessOnBehalfToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(BusinessOnBehalfToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollection */
        $companyUserCollection = $this->zedRequestClient->call(
            '/business-on-behalf/gateway/find-active-company-users-by-customer-id',
            $customerTransfer
        );

        return $companyUserCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUser */
        $companyUserTransfer = $this->zedRequestClient->call(
            '/business-on-behalf/gateway/set-default-company-user',
            $companyUserTransfer
        );

        return $companyUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function unsetDefaultCompanyUser(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUser */
        $companyUserTransfer = $this->zedRequestClient->call(
            '/business-on-behalf/gateway/unset-default-company-user',
            $customerTransfer
        );

        return $customerTransfer;
    }
}
