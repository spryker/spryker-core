<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence;

use Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper\ProductConfigurationMapper;
use Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper\ProductConfigurationMapperInterface;

/**
 * @method \Spryker\Zed\ProductConfiguration\Persistence\ProductConfigurationRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductConfiguration\Persistence\SpyProductConfigurationQuery
     */
    public function createProductConfigurationQuery(): SpyProductConfigurationQuery
    {
        return SpyProductConfigurationQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper\ProductConfigurationMapperInterface
     */
    public function createProductConfigurationMapper(): ProductConfigurationMapperInterface
    {
        return new ProductConfigurationMapper();
    }
}
