<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductImage\Persistence\Propel\Mapper\ProductImageMapper;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface getRepository()
 */
class ProductImagePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery
     */
    public function createProductImageSetToProductImageQuery()
    {
        return SpyProductImageSetToProductImageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery
     */
    public function createProductImageSetQuery()
    {
        return SpyProductImageSetQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function createProductImageQuery()
    {
        return SpyProductImageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductImage\Persistence\Propel\Mapper\ProductImageMapper
     */
    public function createProductImageMapper(): ProductImageMapper
    {
        return new ProductImageMapper();
    }
}
