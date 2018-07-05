<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitMapper;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitMapperInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    public function createProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery
     */
    public function createProductPackagingUnitQuery(): SpyProductPackagingUnitQuery
    {
        return SpyProductPackagingUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery
     */
    public function createProductPackagingLeadProductQuery(): SpyProductPackagingLeadProductQuery
    {
        return SpyProductPackagingLeadProductQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitMapperInterface
     */
    public function createProductPackagingUnitMapper(): ProductPackagingUnitMapperInterface
    {
        return new ProductPackagingUnitMapper();
    }
}
