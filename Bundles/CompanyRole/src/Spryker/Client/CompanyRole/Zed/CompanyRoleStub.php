<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Zed;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class CompanyRoleStub implements CompanyRoleStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->zedStub->call('/company-role/gateway/create', $companyRoleTransfer);
    }
}
