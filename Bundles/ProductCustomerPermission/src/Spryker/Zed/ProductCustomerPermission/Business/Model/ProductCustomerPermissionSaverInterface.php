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
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function savePermission(int $idCustomer, int $productId);

    /**
     * Specification:
     *  - Add new products to customer, if they are no assigned yet
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $productIds);

    /**
     * Specification:
     *  - Delete one product from the customer permission list
     *
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $productId);

    /**
     * Specification:
     *  - Delete all products from the customer permission list
     *
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllPermissions(int $idCustomer);

    /**
     * Specification:
     *  - Delete specified products from the customer permission list
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $productIds);
}
