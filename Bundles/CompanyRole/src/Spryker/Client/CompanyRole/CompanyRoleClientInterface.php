<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

interface CompanyRoleClientInterface
{
    /**
     * Specification:
     * - Creates a company role
     * - Creates company role permissions
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleUserTransfer): CompanyRoleResponseTransfer;

    /**
     * Specification:
     * - Updates company role.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function updateCompanyRole(CompanyRoleTransfer $companyRoleTransfer): void;

    /**
     * Specification:
     * - Finds a company role by CompanyRoleTransfer::idCompanyRole
     * - Deletes the company role
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer;

    /**
     * Specification:
     * - Finds a company roles according CompanyRoleCriteriaFilterTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyRoleCollectionTransfer;

    /**
     * Specification:
     * - Finds a company role by CompanyRoleTransfer::idCompanyRole in the transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer;

    /**
     * Specification:
     * - Finds company role permissions
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer;

    /**
     * Specification:
     * - Removes related to the company user roles
     * - Creates relations roles to the company user according CompanyUserTransfer::companyRoleCollection
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer): void;

    /**
     * Specification:
     * - Finds a permission configuration by id company role and id permission
     * - Finds a permission plugin
     * - Fills a configuration signature
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(PermissionTransfer $permissionTransfer): PermissionTransfer;

    /**
     * Specification:
     * - Updates a company role permission configuration
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRolePermissionResponseTransfer
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): CompanyRolePermissionResponseTransfer;

    /**
     * Specification:
     * - Retrieves non-infrastructural company role permissions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findNonInfrastructuralCompanyRolePermissionsByIdCompanyRole(
        CompanyRoleTransfer $companyRoleTransfer
    ): PermissionCollectionTransfer;
}
