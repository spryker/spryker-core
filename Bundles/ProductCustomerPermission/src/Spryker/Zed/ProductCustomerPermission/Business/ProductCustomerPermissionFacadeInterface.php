<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

interface ProductCustomerPermissionFacadeInterface
{
    /**
     * Specification:
     *  - This method will save relation between customer and product
     *
     * @api
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveCustomerProductPermission(int $customerId, int $productId);

    /**
     * Specification:
     *  - This method will remove all products not from the list of $productsIds, which were assigned to this customer before
     *    and add new ones, if they are no assigned yet
     *
     * @api
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveCustomerProductPermissions(int $customerId, array $productIds);
}
