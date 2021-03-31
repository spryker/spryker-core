<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\StorageStrategy;

interface StorageStrategyProviderInterface
{
    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface
     */
    public function provideStorage(): StorageStrategyInterface;
}
