<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency\Service;

interface ProductMeasurementUnitToUtilUnitConversionServiceInterface
{
    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float
     */
    public function getMeasurementUnitExchangeRatio(string $fromCode, string $toCode): float;
}
