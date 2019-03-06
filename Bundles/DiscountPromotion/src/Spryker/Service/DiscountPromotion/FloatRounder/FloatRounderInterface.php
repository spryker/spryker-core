<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DiscountPromotion\FloatRounder;

interface FloatRounderInterface
{
    /**
     * @param float $value
     *
     * @return float
     */
    public function round(float $value): float;
}
