<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence;

use Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface getRepository()
 */
class DiscountPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherQuery
     */
    public function createDiscountVoucherQuery()
    {
        return SpyDiscountVoucherQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    public function createDiscountQuery()
    {
        return SpyDiscountQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery
     */
    public function createDiscountVoucherPoolQuery()
    {
        return SpyDiscountVoucherPoolQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountAmountQuery
     */
    public function createDiscountAmountQuery()
    {
        return SpyDiscountAmountQuery::create();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountStoreQuery
     */
    public function createDiscountStoreQuery()
    {
        return SpyDiscountStoreQuery::create();
    }
}
