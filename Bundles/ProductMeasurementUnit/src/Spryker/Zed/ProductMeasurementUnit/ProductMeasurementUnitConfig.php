<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitConfig extends AbstractBundleConfig
{
    /**
     * Default measurement unit code.
     */
    public const DEFAULT_MEASUREMENT_UNIT_CODE = 'ITEM';

    /**
     * Infrastructural measurement units list.
     */
    public const INFRASTRUCTURAL_MEASUREMENT_UNITS = [
        [
            'name' => 'measurement_units.item.name',
            'code' => 'ITEM',
            'default_precision' => 1,
        ],
    ];

    /**
     * @return array
     */
    public function getInfrastructuralMeasurementUnits(): array
    {
        return static::INFRASTRUCTURAL_MEASUREMENT_UNITS;
    }

    /**
     * @return string
     */
    public function getDefaultMeasurementUnitCode(): string
    {
        return static::DEFAULT_MEASUREMENT_UNIT_CODE;
    }
}
