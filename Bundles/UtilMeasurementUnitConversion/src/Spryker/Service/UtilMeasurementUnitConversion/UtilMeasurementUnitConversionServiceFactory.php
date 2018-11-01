<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilMeasurementUnitConversion;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilMeasurementUnitConversion\Model\MeasurementUnitConverter;
use Spryker\Service\UtilMeasurementUnitConversion\Model\MeasurementUnitConverterInterface;

/**
 * @method \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionConfig getConfig()
 */
class UtilMeasurementUnitConversionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilMeasurementUnitConversion\Model\MeasurementUnitConverterInterface
     */
    public function createMeasurementUnitConverter(): MeasurementUnitConverterInterface
    {
        return new MeasurementUnitConverter($this->getConfig());
    }
}
