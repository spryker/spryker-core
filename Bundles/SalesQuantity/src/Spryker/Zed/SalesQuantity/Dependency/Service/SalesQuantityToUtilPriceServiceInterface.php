<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Dependency\Service;

interface SalesQuantityToUtilPriceServiceInterface
{
    /**
     * @param float $price
     *
     * @return int
     */
    public function roundPrice(float $price): int;
}
