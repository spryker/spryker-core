<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency\Service;

class ProductMeasurementUnitToUtilMeasurementUnitConversionServiceBridge implements ProductMeasurementUnitToUtilMeasurementUnitConversionServiceInterface
{
    /**
     * @var \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionServiceInterface
     */
    protected $utilMeasurementUnitConversionService;

    /**
     * @param \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionServiceInterface $utilMeasurementUnitConversionService
     */
    public function __construct($utilMeasurementUnitConversionService)
    {
        $this->utilMeasurementUnitConversionService = $utilMeasurementUnitConversionService;
    }

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float
     */
    public function getMeasurementUnitExchangeRatio(string $fromCode, string $toCode): float
    {
        return $this->utilMeasurementUnitConversionService->getMeasurementUnitExchangeRatio($fromCode, $toCode);
    }
}
