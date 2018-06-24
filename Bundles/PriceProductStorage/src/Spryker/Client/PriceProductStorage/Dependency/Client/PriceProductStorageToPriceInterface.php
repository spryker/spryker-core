<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Dependency\Client;

interface PriceProductStorageToPriceInterface
{
    /**
     * @return string
     */
    public function getCurrentPriceMode(): string;
}
