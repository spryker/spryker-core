<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapper;
use Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapperInterface;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface getRepository()
 */
class ProductAlternativePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    public function createProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Persistence\Mapper\ProductAlternativeMapperInterface
     */
    public function createProductAlternativeMapper(): ProductAlternativeMapperInterface
    {
        return new ProductAlternativeMapper();
    }
}
