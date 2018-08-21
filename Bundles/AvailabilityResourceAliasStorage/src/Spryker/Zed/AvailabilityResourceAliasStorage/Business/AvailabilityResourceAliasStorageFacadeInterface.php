<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Business;

interface AvailabilityResourceAliasStorageFacadeInterface
{
    /**
     * Specification:
     *  - Fills/updates sku field in product availability storage table.
     *  - Value of this field is used for exporting mapping resources.
     *
     * @api
     *
     * @param int[] $availabilityIds
     *
     * @return void
     */
    public function updateAvailabilityStorageSkus(array $availabilityIds): void;
}
