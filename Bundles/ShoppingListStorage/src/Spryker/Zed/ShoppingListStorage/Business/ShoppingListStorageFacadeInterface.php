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
     * - Gets Customer References from Shopping Lists;
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
     * - Gets Customer References from Customer related to Company User;
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
     * - Gets Customer References from Customer related to Company User;
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
     * - Publishes Shopping List changes to storage.
     *
     * @api
     *
     * @param string[] $customerReferences
     *
     * @return void
     */
    public function publish(array $customerReferences): void;
}
