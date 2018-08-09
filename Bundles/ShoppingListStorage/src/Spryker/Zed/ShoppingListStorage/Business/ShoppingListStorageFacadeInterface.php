<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business;

interface ShoppingListStorageFacadeInterface
{
    /**
     * Specification:
     * - Selects CustomerReferences from ShoppingList;
     * - Returns array of CustomerReferences.
     *
     * @api
     *
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByShoppingListIds(array $shoppingListIds): array;

    /**
     * Specification:
     * - Selects CustomerReferences from CompanyUser joined with Customer by IdCompanyUser;
     * - Returns array of CustomerReferences.
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
     * - Selects CustomerReferences from CompanyUser joined with Customer by FkCompanyBusinessUnit;
     * - Returns array of CustomerReferences.
     *
     * @api
     *
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * Specification:
     * - Gets actual ShoppingLists by customerReferences and makes ShoppingListCustomerStorage
     * - Fills Data with ShoppingListCustomerStorageTransfer where set UpdatedAt to current timestamp.
     * - Stores SpyShoppingListCustomerStorage;
     *
     * @api
     *
     * @param string[] $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void;
}
