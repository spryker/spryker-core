<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability\Checker;

interface StockCheckerInterface
{
    /**
     * @param string $productConcretesNeverOutOfStockSet
     *
     * @return bool
     */
    public function isAbstractProductNeverOutOfStock(string $productConcretesNeverOutOfStockSet): bool;
}
