<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilMeasurementUnitConversion;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilMeasurementUnitConversion\UtilMeasurementUnitConversionServiceFactory getFactory()
 */
class UtilMeasurementUnitConversionService extends AbstractService implements UtilMeasurementUnitConversionServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float
     */
    public function getMeasurementUnitExchangeRatio(string $fromCode, string $toCode): float
    {
        return $this->getFactory()
            ->createMeasurementUnitConverter()
            ->getExchangeRatio($fromCode, $toCode);
    }
}
