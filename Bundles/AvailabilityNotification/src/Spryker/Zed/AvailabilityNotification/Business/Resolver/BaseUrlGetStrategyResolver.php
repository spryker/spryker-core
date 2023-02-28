<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Resolver;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface;

class BaseUrlGetStrategyResolver implements BaseUrlGetStrategyResolverInterface
{
    /**
     * @var list<\Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface>
     */
    protected array $baseUrlGetStrategies;

    /**
     * @param list<\Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface> $baseUrlGetStrategies
     */
    public function __construct(array $baseUrlGetStrategies)
    {
        $this->baseUrlGetStrategies = $baseUrlGetStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \Spryker\Zed\AvailabilityNotification\Business\Strategy\BaseUrlGetStrategyInterface|null
     */
    public function resolveBaseUrlGetStrategy(?StoreTransfer $storeTransfer = null): ?BaseUrlGetStrategyInterface
    {
        foreach ($this->baseUrlGetStrategies as $baseUrlGetStrategy) {
            if ($baseUrlGetStrategy->isApplicable($storeTransfer)) {
                return $baseUrlGetStrategy;
            }
        }

        return null;
    }
}
