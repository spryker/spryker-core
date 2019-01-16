<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Dependency\Client;

use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;

class CompanyRolesRestApiToCompanyRoleClientBridge implements CompanyRolesRestApiToCompanyRoleClientInterface
{
    /**
     * @var \Spryker\Client\CompanyRole\CompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @param \Spryker\Client\CompanyRole\CompanyRoleClientInterface $companyRoleClient
     */
    public function __construct($companyRoleClient)
    {
        $this->companyRoleClient = $companyRoleClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function findCompanyRoleByUuid(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->companyRoleClient->findCompanyRoleByUuid($companyRoleTransfer);
    }
}
