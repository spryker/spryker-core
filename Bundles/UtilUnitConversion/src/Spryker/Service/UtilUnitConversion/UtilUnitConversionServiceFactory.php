<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilUnitConversion\Model\MeasurementUnitConverter;

class UtilUnitConversionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilUnitConversion\Model\MeasurementUnitConverterInterface
     */
    public function createMeasurementUnitConverter()
    {
        return new MeasurementUnitConverter();
    }
}
