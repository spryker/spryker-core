<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity;

use Spryker\Shared\SalesQuantity\SalesQuantityConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesQuantityConfig extends AbstractBundleConfig
{
    /**
     * @return int|null
     */
    public function findItemQuantityThreshold(): ?int
    {
        $threshold = $this->get(SalesQuantityConstants::ITEM_NONSPLIT_QUANTITY_THRESHOLD, false);

        if ($threshold === false) {
            return null;
        }

        return (int)$threshold;
    }
}
