<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainer getQueryContainer()
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

}
