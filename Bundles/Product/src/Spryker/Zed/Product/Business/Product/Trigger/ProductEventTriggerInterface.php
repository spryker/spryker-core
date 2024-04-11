<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Trigger;

interface ProductEventTriggerInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function triggerProductAbstractUpdateEvents(array $productAbstractIds): void;

    /**
     * @param array<int> $productIds
     *
     * @return void
     */
    public function triggerProductUpdateEvents(array $productIds): void;
}
