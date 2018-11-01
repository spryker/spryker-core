<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilMeasurementUnitConversion\Model;

use Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException;
use Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionConfig;

class MeasurementUnitConverter implements MeasurementUnitConverterInterface
{
    protected const ERROR_INVALID_EXCHANGE = 'There is no exchange ratio defined between "%s" and "%s" measurement unit codes.';

    /**
     * @var \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionConfig
     */
    protected $utilMeasurementUnitConversionConfig;

    /**
     * @param \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionConfig $utilMeasurementUnitConversionConfig
     */
    public function __construct(UtilMeasurementUnitConversionConfig $utilMeasurementUnitConversionConfig)
    {
        $this->utilMeasurementUnitConversionConfig = $utilMeasurementUnitConversionConfig;
    }

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @throws \Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException
     *
     * @return float
     */
    public function getExchangeRatio(string $fromCode, string $toCode): float
    {
        $exchangeRatioMap = $this->utilMeasurementUnitConversionConfig->getMeasurementUnitExchangeRatioMap();

        if (isset($exchangeRatioMap[$fromCode][$toCode])) {
            return $exchangeRatioMap[$fromCode][$toCode];
        }

        throw new InvalidMeasurementUnitExchangeException(
            sprintf(static::ERROR_INVALID_EXCHANGE, $fromCode, $toCode)
        );
    }
}
