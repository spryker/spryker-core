<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductBarcode\Persistence\Mapper\ProductMapper;
use Spryker\Zed\ProductBarcode\Persistence\Mapper\ProductMapperInterface;

/**
 * @method \Spryker\Zed\ProductBarcode\ProductBarcodeConfig getConfig()
 */
class ProductBarcodePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createSpyProductQuery(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductBarcode\Persistence\Mapper\ProductMapperInterface
     */
    public function createProductMapper(): ProductMapperInterface
    {
        return new ProductMapper();
    }
}
