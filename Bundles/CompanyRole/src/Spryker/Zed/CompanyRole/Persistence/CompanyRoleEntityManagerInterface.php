<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

interface CompanyRoleEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function saveCompanyRole(
        CompanyRoleTransfer $companyRoleTransfer
    ): CompanyRoleTransfer;

    /**
     * @api
     *
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function deleteCompanyRoleById(int $idCompanyRole): void;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer[] $permissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function addPermissions(array $permissions, int $idCompanyRole): void;

    /**
     * @api
     *
     * @param array $idPermissions
     * @param int $idCompanyRole
     *
     * @return void
     */
    public function removePermissions(array $idPermissions, int $idCompanyRole): void;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): void;
}
