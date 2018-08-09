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
     * - Uses ShoppingList Propel Query;
     * - Filters by IdShoppingList using first param as data;
     * - Selects CustomerReference column that persists in ShoppingList;
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
     * - Uses CompanyUser Propel Query joined with Customer;
     * - Filters it by IdCompanyUser using first param as data;
     * - Selects CustomerReference column that persists in Customer;
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
     * - Uses CompanyUser Propel Query joined with Customer;
     * - Filters it by FkCompanyBusinessUnit using first param as data;
     * - Selects CustomerReference column that persists in Customer;
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
     * - Uses ShoppingList Propel Query;
     * - Gets actual ShoppingList filtered by CustomerReference using first param;
     * - Uses ShoppingListCustomerStorage Propel Query;
     * - Gets stored ShoppingListCustomerStorage filtered CustomerReference using first param;
     * - Goes by actual ShoppingList with matching records in stored ShoppingListCustomerStorage by CustomerReference;
     * - Creates record in ShoppingListCustomerStorage if no matches found;
     * - Fills Data of SpyShoppingListCustomerStorage by ShoppingListCustomerStorageTransfer with set UpdatedAt by
     *   current timestamp.
     * - saves SpyShoppingListCustomerStorage;
     *
     * @api
     *
     * @param string[] $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void;
}
