<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserFacadeInterface
{
    /**
     * Specification:
     * - Executes CompanyUserSavePreCheckPluginInterface check plugins before company user create.
     * - Creates a company user
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Executes CompanyUserSavePreCheckPluginInterface check plugins before initial company user create.
     * - Creates an initial company user
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function createInitialCompanyUser(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     * - Executes CompanyUserSavePreCheckPluginInterface check plugins before company user update.
     * - Updates a company user
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function update(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Deletes a company user
     * - Anonymize assigned customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function delete(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Retrieves company user information by customer ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer;

    /**
     * Specification:
     * - Retrieves company user information by customer ID
     * - Checks activity flag in a related company
     * - Returns NULL when an activity flag is false
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer;

    /**
     * Specification:
     * - Retrieves active company users collection by customer reference.
     * - Checks activity flag in a related company and company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getActiveCompanyUsersByCustomerReference(
        CustomerTransfer $customerTransfer
    ): CompanyUserCollectionTransfer;

    /**
     * Specification:
     * - Retrieves user companies which can filtered by company ID, user ID and/or active flag.
     * - Ignores company users with anonymised customers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer;

    /**
     * Specification:
     * - Retrieves company user by id
     * - Hydrates company field
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;

    /**
     * Specification:
     * - Retrieves initial company user (first created) by $idCompany,
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findInitialCompanyUserByCompanyId(int $idCompany): ?CompanyUserTransfer;

    /**
     * Specification:
     * - Retrieves count of company user information by customer ID
     * - Checks activity flag in a related company
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function countActiveCompanyUsersByIdCustomer(CustomerTransfer $customerTransfer): int;

    /**
     * Specification:
     * - Returns customer references of customers related to company users;
     *
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array;

    /**
     * Specification:
     * - Enables company user.
     * - Uses idCompanyUser from company user transfer to find company user.
     * - Sets company user's 'is_active' flag to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function enableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Disables company user.
     * - Uses idCompanyUser from company user transfer to find company user.
     * - Sets company user's 'is_active' flag to false.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function disableCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Executes CompanyUserPreDeletePluginInterface plugins before delete company user.
     * - Deletes a company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function deleteCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Finds company user by ID.
     * - Executes CompanyUserHydrationPluginInterface plugins if company user exists.
     * - Returns null if company user does not exist.
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserById(int $idCompanyUser): ?CompanyUserTransfer;

    /**
     * Specification:
     * - Finds active company user by uuid.
     * - Requires uuid field to be set in CompanyUserTransfer.
     * - Uuid is not a required field and could be missing.
     *
     * @api
     *
     * {@internal will work if uuid field is provided by another module.}
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByUuid(CompanyUserTransfer $companyUserTransfer): ?CompanyUserTransfer;

    /**
     * Specification
     *  - Retrieves active company users collection by company user ids.
     *  - Checks activity flag in related company and company user.
     *  - Checks whether related company is approved.
     *  - Checks whether related customer is not anonymized.
     *
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function findActiveCompanyUsersByIds(array $companyUserIds): array;

    /**
     * Specification
     *  - Retrieves active company user ids by company ids.
     *  - Checks activity flag in company user.
     *  - Checks whether related customer is not anonymized.
     *
     * @api
     *
     * @param int[] $companyIds
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCompanyIds(array $companyIds): array;

    /**
     * Specification:
     * - Retrieves company user collection according provided criteria.
     * - Searches "pattern" by at least one of first name, last name, and email.
     * - Applies "limit" when provided.
     * - Populates "Customer" and "Company" properties in returned company users.
     * - Applies "CompanyUserHydrationPluginInterface" plugins on returned company users.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByCriteria(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer;
}
