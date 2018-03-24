<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUnitConversion\Model;

interface MeasurementUnitConverterInterface
{
    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @return float|null
     */
    public function findExchangeRatio($fromCode, $toCode);
}
