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
     * - rounds given quantity.
     *
     * @api
     *
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float;
}
