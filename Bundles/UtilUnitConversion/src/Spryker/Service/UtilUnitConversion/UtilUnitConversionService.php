<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilUnitConversion\UtilUnitConversionServiceFactory getFactory()
 */
class UtilUnitConversionService extends AbstractService implements UtilUnitConversionServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string $fromCode
     * @param string $toCode
     *
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
