<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Dependency\Service;

class ProductMeasurementUnitToUtilUnitConversionServiceBridge implements ProductMeasurementUnitToUtilUnitConversionServiceInterface
{
    /**
     * @var \Spryker\Service\UtilUnitConversion\UtilUnitConversionServiceInterface
     */
    protected $utilUnitConversionService;

    /**
     * @param \Spryker\Service\UtilUnitConversion\UtilUnitConversionServiceInterface $utilUnitConversionService
     */
    public function __construct($utilUnitConversionService)
    {
        $this->utilUnitConversionService = $utilUnitConversionService;
    }

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float|null
     */
    public function findMeasurementUnitExchangeRatio($fromCode, $toCode)
    {
        return $this->utilUnitConversionService->findMeasurementUnitExchangeRatio($fromCode, $toCode);
    }
}
