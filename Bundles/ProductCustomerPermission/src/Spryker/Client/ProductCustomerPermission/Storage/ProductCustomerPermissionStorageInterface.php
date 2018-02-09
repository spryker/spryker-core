<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission\Storage;

interface ProductCustomerPermissionStorageInterface
{
    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function hasProductCustomerPermission(int $idCustomer, int $idProductAbstract): bool;
}
