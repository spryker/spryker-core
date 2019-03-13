<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilProduct;

/**
 * @method \Spryker\Service\UtilProduct\UtilProductServiceFactory getFactory()
 */
interface UtilProductServiceInterface
{
    /**
     * Specification:
     * - makes rounding operation with price.
     * - converts result to int.
     *
     * @api
     *
     * @param float $price
     *
     * @return int
     */
    public function roundPrice(float $price): int;

    /**
     * Specification:
     * - makes rounding operation with quantity.
     *
     * @api
     *
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float;
}
