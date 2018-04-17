<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilMeasurementUnitConversion;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilMeasurementUnitConversionConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Contains a list of exchange ratios.
     * - First level keys represent the base unit code of conversion,
     * - Second level keys represent the target unit code,
     * - Values are the corresponding exchange ratios.
     * - Conversion ratios are defined both forth and back and also to the same unit.
     *
     * @var array
     */
    protected const MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP = [
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
    public function getMeasurementUnitExchangeRatioMap(): array
    {
        return static::MEASUREMENT_UNIT_EXCHANGE_RATIO_MAP;
    }
}
