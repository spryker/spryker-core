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
     * - Check if current customer has permission to buy specified product
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isAllowedForCurrentCustomer(int $idProductAbstract);

    /**
     * Specification:
     * - Check if specified customer has permission to buy specified product
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isAllowedForCustomer(int $idCustomer, int $idProductAbstract);
}
