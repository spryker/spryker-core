<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Calculation;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Calculation\CalculationServiceFactory getFactory()
 */
class CalculationService extends AbstractService implements CalculationServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function convert(float $value): int
    {
        return $this->getFactory()
            ->createFloatConverter()
            ->convert($value);
    }
}
