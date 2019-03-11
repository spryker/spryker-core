<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesQuantity;

/**
 * @method \Spryker\Service\SalesQuantity\SalesQuantityServiceFactory getFactory()
 */
interface SalesQuantityServiceInterface
{
    /**
     * Specification:
     * - rounds float value with precision and round mode defined in config.
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;

    /**
     * Specification:
     * - rounds float value with precision and round mode defined in config.
     * - converts given value to int.
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function rountToInt(float $value): int;
}
