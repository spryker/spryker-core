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
<<<<<<< HEAD
    public function roundQuantity(float $quantity): float;

    /**
     * Specification:
     * - compares two float quantities.
=======
    public function sumQuantities(float $firstQuantity, float $secondQuantity): float;

    /**
     * Specification:
     * - get max precision of input quantities.
     * - subtract quantities.
     * - rounds result with previously calculated precision.
>>>>>>> 753c90b3b572eb7f52e61ecdccc749a1acc263fb
     *
     * @api
     *
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
<<<<<<< HEAD
     * @return bool
     */
    public function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool;
=======
     * @return float
     */
    public function subtractQuantities(float $firstQuantity, float $secondQuantity): float;
>>>>>>> 753c90b3b572eb7f52e61ecdccc749a1acc263fb
}
