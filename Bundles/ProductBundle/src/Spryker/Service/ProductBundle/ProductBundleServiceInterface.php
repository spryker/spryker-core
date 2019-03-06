<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductBundle;

interface ProductBundleServiceInterface
{
    /**
     * Specification:
     * - casts float number to integer.
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convertToInt(float $value): int;

    /**
     * Specification:
     * - rounds float number via php method floor().
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;
}
