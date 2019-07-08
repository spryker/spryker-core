<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

interface DiscountPromotionEntityManagerInterface
{
    /**
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void;
}
