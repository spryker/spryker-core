<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion\Model;

use Spryker\Service\UtilUnitConversion\UtilUnitConversionConfig;

class MeasurementUnitConverter implements MeasurementUnitConverterInterface
{
    /**
     * @var \Spryker\Service\UtilUnitConversion\UtilUnitConversionConfig
     */
    protected $utilUnitConversionConfig;

    /**
     * @param \Spryker\Service\UtilUnitConversion\UtilUnitConversionConfig $utilUnitConversionConfig
     */
    public function __construct(UtilUnitConversionConfig $utilUnitConversionConfig)
    {
        $this->utilUnitConversionConfig = $utilUnitConversionConfig;
    }

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float|null
     */
    public function findExchangeRatio($fromCode, $toCode)
    {
        $exchangeRatioMap = $this->utilUnitConversionConfig->getMeasurementUnitExchangeRatioMap();

        if (isset($exchangeRatioMap[$fromCode][$toCode])) {
            return $exchangeRatioMap[$fromCode][$toCode];
        }

        return null;
    }
}
