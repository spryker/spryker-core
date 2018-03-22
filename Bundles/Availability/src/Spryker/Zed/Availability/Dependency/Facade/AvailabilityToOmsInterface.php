<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityToOmsInterface
{
    /**
     * @deprecated Using this method will affect the performance,
     * use AvailabilityToOmsInterface::getOmsReservedProductQuantityForSku() instead.
     *
     * @param string $sku
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku, StoreTransfer $storeTransfer);
}
