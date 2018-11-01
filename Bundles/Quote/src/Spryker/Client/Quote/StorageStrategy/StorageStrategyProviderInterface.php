<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

interface StorageStrategyProviderInterface
{
    /**
     * @return \Spryker\Client\Quote\StorageStrategy\StorageStrategyInterface
     */
    public function provideStorage(): StorageStrategyInterface;
}
