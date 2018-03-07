<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\StorageStrategy;

use Spryker\Client\Quote\QuoteClientInterface;

interface StorageStrategyInterface extends QuoteClientInterface
{
    /**
     * @return bool
     */
    public function isAllowed();
}
