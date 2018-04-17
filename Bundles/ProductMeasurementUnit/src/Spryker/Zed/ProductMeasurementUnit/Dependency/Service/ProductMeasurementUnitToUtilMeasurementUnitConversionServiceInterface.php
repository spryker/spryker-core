<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency\Service;

interface ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
{
    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @throws \Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException
     *
     * @return float
     */
    public function getMeasurementUnitExchangeRatio(string $fromCode, string $toCode): float;
}
