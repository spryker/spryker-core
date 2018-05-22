<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Spryker\Shared\ProductMeasurementUnit\ProductMeasurementUnitConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductMeasurementUnitConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getInfrastructuralMeasurementUnits(): array
    {
        return $this->get(ProductMeasurementUnitConstants::INFRASTRUCTURAL_MEASUREMENT_UNITS);
    }

    /**
     * @return string
     */
    public function getDefaultMeasurementUnitCode(): string
    {
        return $this->get(ProductMeasurementUnitConstants::DEFAULT_MEASUREMENT_UNIT_CODE);
    }
}
