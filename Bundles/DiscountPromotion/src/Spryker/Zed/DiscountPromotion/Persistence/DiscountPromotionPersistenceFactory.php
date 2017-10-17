<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainer getQueryContainer()
 */
class DiscountPromotionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function createDiscountPromotionQuery()
    {
        return SpyDiscountPromotionQuery::create();
    }
}
