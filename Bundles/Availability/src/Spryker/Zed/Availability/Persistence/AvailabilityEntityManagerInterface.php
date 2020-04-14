<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilityEntityManagerInterface
{
    /**
     * Specification:
     *  - Returns true if product availability changed.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param string $abstractSku
     *
     * @return bool
     */
    public function saveProductConcreteAvailability(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        StoreTransfer $storeTransfer,
        string $abstractSku
    ): bool;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer
     */
    public function saveProductAbstractAvailability(
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer,
        StoreTransfer $storeTransfer
    ): ProductAbstractAvailabilityTransfer;
}
