<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUsersRestApi\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Spryker\Client\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToZedRequestClientInterface;

class CompanyUsersRestApiStub implements CompanyUsersRestApiStubInterface
{
    /**
     * @var \Spryker\Client\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CompanyUsersRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer */
        $companyUserCollectionTransfer = $this->zedRequestClient->call(
            '/company-users-rest-api/gateway/get-company-user-collection',
            $criteriaFilterTransfer
        );

        return $companyUserCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(
        CustomerCollectionTransfer $customerCollectionTransfer
    ): CustomerCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer */
        $customerCollectionTransfer = $this->zedRequestClient->call(
            '/company-users-rest-api/gateway/get-customer-collection',
            $customerCollectionTransfer
        );

        return $customerCollectionTransfer;
    }
}
