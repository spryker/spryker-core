<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion\Model;

use Spryker\Service\UtilUnitConversion\Exception\InvalidMeasurementUnitExchangeException;
use Spryker\Service\UtilUnitConversion\UtilUnitConversionConfig;

class MeasurementUnitConverter implements MeasurementUnitConverterInterface
{
    const ERROR_INVALID_EXCHANGE = 'There is no exchange ratio defined between "%s" and "%s" measurement unit codes.';

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
     * @throws \Spryker\Service\UtilUnitConversion\Exception\InvalidMeasurementUnitExchangeException
     *
     * @return float
     */
    public function getExchangeRatio(string $fromCode, string $toCode): float
    {
        $exchangeRatioMap = $this->utilUnitConversionConfig->getMeasurementUnitExchangeRatioMap();

        if (isset($exchangeRatioMap[$fromCode][$toCode])) {
            return $exchangeRatioMap[$fromCode][$toCode];
        }

        throw new InvalidMeasurementUnitExchangeException(
            sprintf(static::ERROR_INVALID_EXCHANGE, $fromCode, $toCode)
        );
    }
}
