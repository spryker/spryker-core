<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface AvailabilitySubscriptionMailProcessorInterface
{
    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function processProductBecomeAvailableSubscription(string $sku, StoreTransfer $storeTransfer, ProductConcreteTransfer $productConcreteTransfer): void;
}
