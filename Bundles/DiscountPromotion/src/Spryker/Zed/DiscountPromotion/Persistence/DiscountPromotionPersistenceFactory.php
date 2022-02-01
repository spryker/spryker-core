<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery;
use Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldChecker;
use Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldCheckerInterface;
use Spryker\Zed\DiscountPromotion\Persistence\Propel\Mapper\DiscountPromotionMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 */
class DiscountPromotionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery
     */
    public function createDiscountPromotionQuery(): SpyDiscountPromotionQuery
    {
        return SpyDiscountPromotionQuery::create();
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Persistence\Propel\Mapper\DiscountPromotionMapper
     */
    public function createDiscountPromotionMapper(): DiscountPromotionMapper
    {
        return new DiscountPromotionMapper(
            $this->createDiscountPromotionFieldChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldCheckerInterface
     */
    public function createDiscountPromotionFieldChecker(): DiscountPromotionFieldCheckerInterface
    {
        return new DiscountPromotionFieldChecker();
    }
}
