<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
class DiscountPromotionQueryContainer extends AbstractQueryContainer implements DiscountPromotionQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function queryDiscountPromotionByIdDiscount($idDiscount)
    {
        return $this->getFactory()
            ->createDiscountPromotionQuery()
            ->filterByFkDiscount($idDiscount);
    }

    /**
     * @api
     *
     * @param int $idDiscountPromotion
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function queryDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
    {
        return $this->getFactory()
            ->createDiscountPromotionQuery()
            ->filterByIdDiscountPromotion($idDiscountPromotion);
    }
}
