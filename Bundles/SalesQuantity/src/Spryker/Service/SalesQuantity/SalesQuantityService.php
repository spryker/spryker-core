<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesQuantity;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\SalesQuantity\SalesQuantityServiceFactory getFactory()
 */
class SalesQuantityService extends AbstractService implements SalesQuantityServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return $this->getFactory()
            ->createFloatRounder()
            ->round($value);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function rountToInt(float $value): int
    {
        return $this->getFactory()
            ->createFloatRounder()
            ->roundToInt($value);
    }
}
