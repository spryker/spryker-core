<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Zed;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface;

class CompanyRoleStub implements CompanyRoleStubInterface
{
    /**
     * @var \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CompanyRoleToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->zedRequestClient->call('/company-role/gateway/create', $companyRoleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyRoleCollection(
        CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
    ): CompanyRoleCollectionTransfer {
        return $this->zedRequestClient->call(
            '/company-role/gateway/get-company-role-collection',
            $companyRoleCollectionTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->zedRequestClient->call(
            '/company-role/gateway/get-company-role-by-id',
            $companyRoleTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function updateCompanyRole(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->zedRequestClient->call(
            '/company-role/gateway/update-company-role',
            $companyRoleTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->zedRequestClient->call(
            '/company-role/gateway/delete-company-role',
            $companyRoleTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer
    {
        return $this->zedRequestClient->call(
            '/company-role/gateway/find-company-role-permissions',
            $companyRoleTransfer
        );
    }
}
