<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Dependency\Service;

interface PersistentCartToUtilQuantityServiceInterface
{
    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function sumQuantities(float $firstQuantity, float $secondQuantity): float;

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function subtractQuantities(float $firstQuantity, float $secondQuantity): float;

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool;
}
