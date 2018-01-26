<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

interface CustomerProductPermissionSaverInterface
{
    /**
     * // add specification
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveProductPermission(int $customerId, int $productId);

    /**
     * // add specification
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveProductPermissions(int $customerId, array $productIds);
}
