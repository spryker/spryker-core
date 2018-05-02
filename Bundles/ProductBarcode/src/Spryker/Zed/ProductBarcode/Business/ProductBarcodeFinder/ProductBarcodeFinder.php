<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductBarcodeFinder;

use Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode;

class ProductBarcodeFinder implements ProductBarcodeFinderInterface
{
    /**
     * @param int $productId
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductId(int $productId): SpyProductBarcode
    {
        return $this->getQueryContainer()
            ->queryProductBarcodeByProductId($productId)
            ->findOne();
    }

    /**
     * @param string $productName
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductName(string $productName): SpyProductBarcode
    {
        // TODO: Implement findProductBarcodeByProductName() method.
    }

    /**
     * @param string $productSku
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductSku(string $productSku): SpyProductBarcode
    {
        // TODO: Implement findProductBarcodeByProductName() method.
    }

    /**
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode[]
     */
    public function getAllProductBarcodes(): array
    {
        // TODO: Implement getAllProductBarcodes() method.
    }
}
