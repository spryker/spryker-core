<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductMeasurementUnitStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Key generation resource name of product measurement unit.
     *
     * @api
     */
    public const PRODUCT_MEASUREMENT_UNIT_RESOURCE_NAME = 'product_measurement_unit';

    /**
     * Specification:
     * - Key generation resource name of product concrete measurement unit.
     *
     * @api
     */
    public const PRODUCT_CONCRETE_MEASUREMENT_UNIT_RESOURCE_NAME = 'product_concrete_measurement_unit';
}
