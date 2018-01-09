<?php

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface CompanyStoreManagerInterface
{

    /**
     * Specification:
     * - Assign stores to company
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