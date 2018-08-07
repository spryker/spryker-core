<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Persistence;

use Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage;

interface ShoppingListStorageRepositoryInterface
{
    /**
     * @param int[] $shippingListIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByShippingListIds(array $shippingListIds): array;

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array;

    /**
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array;

    /**
     * @param string $customerReference
     *
     * @return \Orm\Zed\ShoppingListStorage\Persistence\SpyShoppingListCustomerStorage
     */
    public function findShoppingListCustomerStorageEntitiesByCustomerReference(string $customerReference): SpyShoppingListCustomerStorage;
}
