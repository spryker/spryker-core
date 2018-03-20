<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitConfig extends AbstractBundleConfig
{
    const MEASUREMENT_UNIT_EXCHANGE_COLLECTION = [
        'KILO' => [
            'KILO' => [1, 1],
            'GRAM' => [1000, 1],
        ],
        'GRAM' => [
            'GRAM' => [1, 1],
            'KILO' => [1, 1000],
        ],
        'METR' => [
            'METR' => [1, 1],
            'CMET' => [1, 100],
        ],
        'CMET' => [
            'CMET' => [1, 1],
            'METR' => [100, 1],
        ],
    ];
}
