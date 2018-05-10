<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Persistence;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductBarcodeGui\Persistence\ProductBarcodeGuiQueryContainerInterface getQueryContainer()
 */
class ProductBarcodeGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @uses SpyProductQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function createProductConcreteQuery(): SpyProductQuery
    {
        return new SpyProductQuery();
    }
}
