<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion\Model;

class MeasurementUnitConverter implements MeasurementUnitConverterInterface
{
    const MEASUREMENT_UNIT_EXCHANGE_RATIO_COLLECTION = [
        'KILO' => [
            'KILO' => 1,
            'GRAM' => 1000,
        ],
        'GRAM' => [
            'GRAM' => 1,
            'KILO' => 0.001,
        ],
        'METR' => [
            'METR' => 1,
            'CMET' => 100,
        ],
        'CMET' => [
            'CMET' => 1,
            'METR' => 0.01,
        ],
    ];

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float|null
     */
    public function findExchangeRatio($fromCode, $toCode)
    {
        if (isset(static::MEASUREMENT_UNIT_EXCHANGE_RATIO_COLLECTION[$fromCode][$toCode])) {
            return static::MEASUREMENT_UNIT_EXCHANGE_RATIO_COLLECTION[$fromCode][$toCode];
        }

        return null;
    }
}
