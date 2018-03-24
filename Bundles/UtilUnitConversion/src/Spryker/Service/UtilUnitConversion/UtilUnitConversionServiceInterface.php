<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilUnitConversion;

interface UtilUnitConversionServiceInterface
{
    /**
     * Specification:
     * - Retrieves the exchange ratio between the provided measurement unit codes.
     *
     * @api
     *
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float|null
     */
    public function findMeasurementUnitExchangeRatio($fromCode, $toCode);
}
