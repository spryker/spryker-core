<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser\Zed;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function findCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        return $this->zedRequestClient->call('/company-user/gateway/find-company-user-by-customer-id', $customerTransfer);
    }
}
