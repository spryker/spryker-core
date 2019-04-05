<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

interface UtilQuantityServiceInterface
{
    /**
     * Specification:
     * - compares two float quantities.
     *
     * @api
     *
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     **/
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool;

    /**
     * Specification:
     * - get max precision of input quantities.
     * - sum quantities.
     * - rounds result with previously calculated precision.
     *
     * @api
     *
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function sumQuantities(float $firstQuantity, float $secondQuantity): float;

    /**
     * Specification:
     * - get max precision of input quantities.
     * - subtract quantities.
     * - rounds result with previously calculated precision.
     *
     * @api
     *
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return float
     */
    public function subtractQuantities(float $firstQuantity, float $secondQuantity): float;

    /**
     * Specification:
     * - checks whether numbers divide with specified remainder.
     *
     * @api
     *
     * @param float $dividendQuantity
     * @param float $divisorQuantity
     * @param float $remainder
     *
     * @return bool
     */
    public function isQuantityModuloEqual(float $dividendQuantity, float $divisorQuantity, float $remainder): bool;
}
