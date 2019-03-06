<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductBundle\FloatConverter;

interface FloatConverterInterface
{
    /**
     * @param float $value
     *
     * @return int
     */
    public function convertToInt(float $value): int;

    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;
}
