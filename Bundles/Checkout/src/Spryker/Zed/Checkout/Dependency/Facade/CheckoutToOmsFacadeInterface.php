<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Dependency\Facade;

interface CheckoutToOmsFacadeInterface
{
    /**
     * @param array $orderItemIds
     * @param array<string, mixed> $data
     *
     * @return array
     */
    public function triggerEventForNewOrderItems(array $orderItemIds, array $data = []);
}
