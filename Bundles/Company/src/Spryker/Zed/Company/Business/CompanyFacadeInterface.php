<?php

namespace Spryker\Zed\Company\Business;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanyFacadeInterface
{

    /**
     * Specification:
     * - Creates a company
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function create(CompanyTransfer $companyTransfer);

    /**
     * Specification:
     * - Finds a company by CompanyTransfer::idCompany in the transfer
     * - Updates fields in a company entity
     * - Updates relation to stores
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function update(CompanyTransfer $companyTransfer);

    /**
     * Specification:
     * - Finds a company by CompanyTransfer::idCompany in the transfer
     * - Deletes the company
     *
     * @param CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function delete(CompanyTransfer $companyTransfer);

}