<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DiscountPromotion;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\DiscountPromotion\DiscountPromotionServiceFactory getFactory()
 */
class DiscountPromotionService extends AbstractService implements DiscountPromotionServiceInterface
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
}
