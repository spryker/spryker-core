<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilMeasurementUnitConversion;

interface UtilMeasurementUnitConversionServiceInterface
{
    /**
     * Specification:
     * - Retrieves the exchange ratio between the provided measurement unit codes.
     * - Throws exception if no exchange ratio was found for the given codes.
     *
     * @api
     *
     * @param string $fromCode
     * @param string $toCode
     *
     * @throws \Spryker\Service\UtilMeasurementUnitConversion\Exception\InvalidMeasurementUnitExchangeException
     *
     * @return float
     */
    public function getMeasurementUnitExchangeRatio(string $fromCode, string $toCode): float;
}
