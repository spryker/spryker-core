<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
class DiscountPromotionEntityManager extends AbstractEntityManager implements DiscountPromotionEntityManagerInterface
{
    /**
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void
    {
        $this->getFactory()
            ->createDiscountPromotionQuery()
            ->filterByFkDiscount($idDiscount)
            ->delete();
    }
}
