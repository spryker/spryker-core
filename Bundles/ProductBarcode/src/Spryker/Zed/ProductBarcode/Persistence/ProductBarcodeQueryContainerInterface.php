<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Persistence;

use Orm\Zed\ProductBarcode\Persistence\SpyProductBarcodeQuery;

interface ProductBarcodeQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcodeQuery
     */
    public function queryProductBarcode(): SpyProductBarcodeQuery;

    /**
     * @api
     *
     * @param int $productId
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcodeQuery
     */
    public function queryProductBarcodeByProductId(int $productId): SpyProductBarcodeQuery;
}
