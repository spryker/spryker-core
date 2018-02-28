<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserClientInterface
{
    /**
     * Specification:
     * - Creates new company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Updates company user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function updateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;

    /**
     * Specification:
     * - Deletes company user.
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
     * - Retrieves company users collection by company id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUserCollectionTransfer;

    /**
     * Specification:
     * - Retrieves company user by id
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer;
}
