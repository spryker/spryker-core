<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DiscountPromotion\FloatRounder;

use Spryker\Service\DiscountPromotion\DiscountPromotionConfig;

class FloatRounder implements FloatRounderInterface
{
    /**
     * @var \Spryker\Service\DiscountPromotion\DiscountPromotionConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\DiscountPromotion\DiscountPromotionConfig $config
     */
    public function __construct(DiscountPromotionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float
    {
        return round($value, $this->config->getRoundPrecision(), $this->config->getRoundMode());
    }
}
