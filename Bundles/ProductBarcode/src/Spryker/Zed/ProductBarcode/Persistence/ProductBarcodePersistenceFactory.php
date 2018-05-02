<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Persistence;

use Orm\Zed\ProductBarcode\Persistence\SpyProductBarcodeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductBarcode\ProductBarcodeConfig getConfig()
 * @method \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeQueryContainerInterface getQueryContainer()
 */
class ProductBarcodePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcodeQuery
     */
    public function createSpyProductBarcodeQuery()
    {
        return SpyProductBarcodeQuery::create();
    }
}
