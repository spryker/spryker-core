<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Resolver;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface;

interface BaseUrlGetStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface|null
     */
    public function resolveBaseUrlGetStrategy(?StoreTransfer $storeTransfer = null): ?BaseUrlGetStrategyInterface;
}
