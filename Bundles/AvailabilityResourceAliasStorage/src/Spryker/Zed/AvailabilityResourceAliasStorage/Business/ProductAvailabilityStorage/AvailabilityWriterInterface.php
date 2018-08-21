<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityResourceAliasStorage\Business\ProductAvailabilityStorage;

interface AvailabilityWriterInterface
{
    /**
     * @param int[] $availabilityIds
     *
     * @return void
     */
    public function updateAvailabilityStorageSkus(array $availabilityIds): void;
}
