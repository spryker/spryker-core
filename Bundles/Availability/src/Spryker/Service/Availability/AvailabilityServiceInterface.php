<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability;

interface AvailabilityServiceInterface
{
    /**
     * Specification:
     * - Parses `productConcretesNeverOutOfStockSet` based on pattern.
     * - Returns `true` if at least one value in set is considered as positive.
     * - Otherwise, returns `false`.
     * - `productConcretesNeverOutOfStockSet` should contain a list of product concretes' `isNeverOutOfStock` values joined to a string.
     *
     * @api
     *
     * @param string $productConcretesNeverOutOfStockSet
     *
     * @return bool
     */
    public function isAbstractProductNeverOutOfStock(string $productConcretesNeverOutOfStockSet): bool;
}
