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
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getProductCustomerPermission(int $idCustomer, int $idProductAbstract);
}
