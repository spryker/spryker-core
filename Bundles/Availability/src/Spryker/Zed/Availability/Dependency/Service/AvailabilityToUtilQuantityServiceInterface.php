<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Service;

interface AvailabilityToUtilQuantityServiceInterface
{
    /**
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float;

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool;
}
