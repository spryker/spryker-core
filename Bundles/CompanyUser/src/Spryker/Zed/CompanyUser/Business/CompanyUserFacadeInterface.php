<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CompanyUserFacadeInterface
{
    /**
     * Specification:
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
     * - Creates an initial company user
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createInitialCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
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
     * - Retrieves company users collection.
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
}
