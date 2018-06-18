<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Persistence;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingLeadProductMapper;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingLeadProductMapperInterface;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitTypeMapper;
use Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitTypeMapperInterface;

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
     * @return \Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingUnitTypeMapperInterface
     */
    public function createProductPackagingUnitTypeMapper(): ProductPackagingUnitTypeMapperInterface
    {
        return new ProductPackagingUnitTypeMapper();
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingLeadProductQuery
     */
    public function createProductPackagingLeadProductQuery(): SpyProductPackagingLeadProductQuery
    {
        return SpyProductPackagingLeadProductQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Persistence\Propel\Mapper\ProductPackagingLeadProductMapperInterface
     */
    public function createProductPackagingLeadProductMapper(): ProductPackagingLeadProductMapperInterface
    {
        return new ProductPackagingLeadProductMapper();
    }
}
