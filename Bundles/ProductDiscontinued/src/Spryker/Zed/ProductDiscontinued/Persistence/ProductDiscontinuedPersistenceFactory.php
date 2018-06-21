<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper\ProductDiscontinuedMapper;
use Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper\ProductDiscontinuedMapperInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 */
class ProductDiscontinuedPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper\ProductDiscontinuedMapperInterface
     */
    public function createProductDiscontinuedMapper(): ProductDiscontinuedMapperInterface
    {
        return new ProductDiscontinuedMapper();
    }

    /**
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery
     */
    public function createProductDiscontinuedQuery(): SpyProductDiscontinuedQuery
    {
        return SpyProductDiscontinuedQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery
     */
    public function createProductDiscontinuedNoteQuery(): SpyProductDiscontinuedNoteQuery
    {
        return SpyProductDiscontinuedNoteQuery::create();
    }
}
