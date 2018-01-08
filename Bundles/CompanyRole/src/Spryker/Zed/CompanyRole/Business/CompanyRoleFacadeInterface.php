<?php

namespace Spryker\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyRoleFacadeInterface
{
    /**
     * Specification:
     * - Creates a company role
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer);

    /**
     * Specification:
     * - Finds a company role by CompanyRoleTransfer::idCompanyRole in the transfer
     * - Updates fields in a company role entity
     * - Finds/creates permissions according CompanyRoleTransfer::permission and updates configuration in them
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function update(CompanyRoleTransfer $companyRoleTransfer);

    /**
     * Specification:
     * - Finds a company role by CompanyRoleTransfer::idCompanyRole
     * - Deletes the company role
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function delete(CompanyRoleTransfer $companyRoleTransfer);

    /**
     * Specification:
     * - Finds all assigned roles to the company user
     *
     * @param int $idCompanyUser
     *
     * @return CompanyRoleCollectionTransfer
     */
    public function findByIdCompanyUser($idCompanyUser);

    /**
     * Specification:
     * - Removes related to the company user roles
     * - Creates relations roles to the company user according CompanyUserTransfer::companyRoleCollection
     *
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer);

    /**
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return CompanyUserTransfer
     */
    public function hydrateCompanyUser(CompanyUserTransfer $companyUserTransfer);
}