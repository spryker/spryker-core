<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilUnitConversionConfig extends AbstractBundleConfig
{
    const MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP = [
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
     * @return array
     */
    public function getMeasurementUnitExchangeRatioMap()
    {
        return static::MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP;
    }
}
