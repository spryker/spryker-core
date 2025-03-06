<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business;

use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;

interface CompanyFacadeInterface
{
    /**
     * Specification:
     *  - Retrieve a company by CompanyTransfer::idCompany in the transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompanyById(CompanyTransfer $companyTransfer): CompanyTransfer;

    /**
     * Specification:
     * - Creates a company
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function create(CompanyTransfer $companyTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     * - Finds a company by CompanyTransfer::idCompany in the transfer
     * - Updates fields in a company entity
     * - Updates relations to stores
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function update(CompanyTransfer $companyTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     * - Finds a company by CompanyTransfer::idCompany in the transfer
     * - Deletes the company
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function delete(CompanyTransfer $companyTransfer): void;

    /**
     * Specification:
     * - Retrieves collection of all companies
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyCollectionTransfer
     */
    public function getCompanies(): CompanyCollectionTransfer;

    /**
     * Specification:
     * - Finds a company by id.
     * - Returns null if company does not exist.
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyById(int $idCompany): ?CompanyTransfer;

    /**
     * Specification:
     * - Finds a company by uuid.
     * - Requires uuid field to be set in CompanyTransfer taken as parameter.
     *
     * @api
     *
     * {@internal will work if UUID field is provided.}
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function findCompanyByUuid(CompanyTransfer $companyTransfer): CompanyResponseTransfer;

    /**
     * Specification:
     * - Retrieves company entities filtered by criteria from Persistence.
     * - Uses `CompanyCriteriaFilterTransfer.idCompany` to filter by specific company ID.
     * - Uses `CompanyCriteriaFilterTransfer.companyIds` to filter by multiple company IDs.
     * - Uses `CompanyCriteriaFilterTransfer.name` to filter companies by name (case-insensitive partial match).
     * - Uses `CompanyCriteriaFilterTransfer.filter.limit` to limit the number of results.
     * - Returns `CompanyCollectionTransfer` containing filtered company data.
     * - Returns empty collection if no companies match the criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyCriteriaFilterTransfer $companyCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyCollectionTransfer
     */
    public function getCompanyCollection(CompanyCriteriaFilterTransfer $companyCriteriaFilterTransfer): CompanyCollectionTransfer;
}
