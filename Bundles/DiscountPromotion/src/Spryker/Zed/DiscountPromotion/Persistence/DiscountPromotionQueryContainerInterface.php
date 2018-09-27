<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
interface DiscountPromotionQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idDiscount
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function queryDiscountPromotionByIdDiscount($idDiscount);

    /**
     * @api
     *
     * @param int $idDiscountPromotion
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function queryDiscountPromotionByIdDiscountPromotion($idDiscountPromotion);
}
