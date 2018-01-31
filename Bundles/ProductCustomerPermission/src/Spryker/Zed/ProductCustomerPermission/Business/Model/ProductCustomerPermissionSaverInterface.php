<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

interface ProductCustomerPermissionSaverInterface
{
    /**
     * Specification:
     *  - Add one product to the customer permission list
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function savePermission(int $customerId, int $productId);

    /**
     * Specification:
     *  - Add new products to customer, if they are no assigned yet
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function savePermissions(int $customerId, array $productIds);

    /**
     * Specification:
     *  - Delete one product from the customer permission list
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function deletePermission(int $customerId, int $productId);

    /**
     * Specification:
     *  - Delete all products from the customer permission list
     *
     * @param int $customerId
     *
     * @return void
     */
    public function deleteAllPermissions(int $customerId);

    /**
     * Specification:
     *  - Delete specified products from the customer permission list
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function deletePermissions(int $customerId, array $productIds);
}
