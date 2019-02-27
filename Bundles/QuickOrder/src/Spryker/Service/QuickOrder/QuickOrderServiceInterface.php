<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\QuickOrder;

interface QuickOrderServiceInterface
{
    /**
     * Specification:
     * - rounds float value using precision and round mode defined in config.
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;
}
