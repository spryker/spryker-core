<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;

interface CompanyRoleFacadeInterface
{
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
     * - Creates a company role
     * - Creates company role permission relations
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer;

    /**
     * Specification:
     * - Creates a company role by for a company
     * - Fills default name from a module configuration
     * - Creates company role permission relations
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createByCompany(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     * - Finds a company role by CompanyRoleTransfer::idCompanyRole in the transfer
     * - Updates fields in a company role entity
     * - Finds/creates/updates permissions according CompanyRoleTransfer::permissionCollection and updates
     * configuration in them
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function update(CompanyRoleTransfer $companyRoleTransfer): void;

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
    public function delete(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer;

    /**
     * Specification:
     * - Finds company roles
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRoles(): CompanyRoleCollectionTransfer;

    /**
     * Specification:
     * - Finds company role permissions
     *
     * @api
     *
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(int $idCompanyRole): PermissionCollectionTransfer;

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
     * - Hydrates a list of assigned to a company user permissions
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;

    /**
     * Specification:
     * - Collects related to a company user permissions from all assigned roles
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer;

    /**
     * Specification:
     * - Finds a permission for a role
     * - Hydrates permission
     * - Returns an empty permission transfer if a desired combination does not exist
     *
     * @api
     *
     * @param int $idCompanyRole
     * @param int $idPermission
     *
     * @return \Generated\Shared\Transfer\PermissionTransfer
     */
    public function findPermissionByIdCompanyRoleByIdPermission(int $idCompanyRole, int $idPermission): PermissionTransfer;

    /**
     * Specification:
     * - Finds company roles according CompanyRoleCriteriaFilterTransfer
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
     * - Updates company role permission configuration
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return void
     */
    public function updateCompanyRolePermission(PermissionTransfer $permissionTransfer): void;

    /**
     * Specification:
     * - Retrieves default company role.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getDefaultCompanyRole(): CompanyRoleTransfer;
}
