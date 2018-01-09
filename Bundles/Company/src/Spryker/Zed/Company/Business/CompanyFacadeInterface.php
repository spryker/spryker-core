<?php

namespace Spryker\Zed\Company\Business;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

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

    /**
     * Specification:
     * - Assigns stores to company
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer[] ...$storeTransfer
     *
     * @return void
     */
    public function assignStores(CompanyTransfer $companyTransfer, StoreTransfer ...$storeTransfer);

    /**
     * Specification:
     * - De assign stores from company
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfer
     *
     * @return void
     */
    public function deAssignStores(CompanyTransfer $companyTransfer, StoreTransfer ...$storeTransfer);

}