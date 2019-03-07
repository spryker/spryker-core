<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Discount\DiscountServiceFactory getFactory()
 */
class DiscountService extends AbstractService implements DiscountServiceInterface
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
    public function roundToInt(float $value): int
    {
        return $this->getFactory()
            ->createFloatRounder()
            ->roundToInt($value);
    }
}
