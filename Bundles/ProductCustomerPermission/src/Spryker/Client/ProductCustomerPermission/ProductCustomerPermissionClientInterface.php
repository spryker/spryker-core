<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission;

interface ProductCustomerPermissionClientInterface
{
    /**
     * Specification:
     * - Checks if specified customer has permission to buy specified product.
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isAllowedForCustomer(int $idCustomer, int $idProductAbstract): bool;
}
