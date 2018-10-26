<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole\Zed;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
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
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyRoleResponseTransfer $companyRoleResponseTransfer */
        $companyRoleResponseTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/create',
            $companyRoleTransfer
        );

        return $companyRoleResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer */
        $companyRoleCollectionTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/get-company-role-collection',
            $criteriaFilterTransfer
        );

        return $companyRoleCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer */
        $companyRoleTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/get-company-role-by-id',
            $companyRoleTransfer
        );

        return $companyRoleTransfer;
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
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyRoleResponseTransfer $companyRoleResponseTransfer */
        $companyRoleResponseTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/delete-company-role',
            $companyRoleTransfer
        );

        return $companyRoleResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer */
        $permissionCollectionTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/find-company-role-permissions',
            $companyRoleTransfer
        );

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->zedRequestClient->call(
            '/company-role/gateway/save-company-user',
            $companyUserTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(PermissionTransfer $permissionTransfer): PermissionTransfer
    {
        /** @var \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer */
        $permissionTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/find-permission-by-id-company-role-by-id-permission',
            $permissionTransfer
        );

        return $permissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): CompanyRolePermissionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer $companyRolePermissionResponseTransfer */
        $companyRolePermissionResponseTransfer = $this->zedRequestClient->call(
            '/company-role/gateway/update-company-role-permission',
            $permissionTransfer
        );

        return $companyRolePermissionResponseTransfer;
    }
}
