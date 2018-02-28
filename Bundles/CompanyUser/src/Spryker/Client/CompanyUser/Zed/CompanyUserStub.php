<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser\Zed;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CompanyUserStub implements CompanyUserStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedRequestClient
     */
    public function __construct(ZedRequestClient $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->zedRequestClient->call('/company-user/gateway/create', $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function updateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->zedRequestClient->call('/company-user/gateway/update', $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->zedRequestClient->call('/company-user/gateway/delete', $companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        return $this->zedRequestClient->call(
            '/company-user/gateway/get-company-user-collection',
            $companyUserCriteriaFilterTransfer
        );
    }
}
