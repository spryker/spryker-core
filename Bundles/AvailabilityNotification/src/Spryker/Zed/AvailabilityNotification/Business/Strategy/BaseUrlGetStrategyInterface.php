<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Strategy;

use Generated\Shared\Transfer\StoreTransfer;

interface BaseUrlGetStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isApplicable(?StoreTransfer $storeTransfer = null): bool;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    public function getBaseUrl(?StoreTransfer $storeTransfer = null): string;
}
