<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode;

interface ProductBarcodeFacadeInterface
{
    /**
     * Specification:
     * - Generates a barcode for the given concrete product with the use of Barcode module service.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, string $generatorPlugin = null): BarcodeResponseTransfer;

    /**
     * Specification:
     * - Returns ProductBarcode instances filtered by product id.
     *
     * @api
     *
     * @param int $productId
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductId(int $productId): SpyProductBarcode;

    /**
     * Specification:
     * - Returns ProductBarcode instances filtered by product name.
     *
     * @api
     *
     * @param string $productName
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductName(string $productName): SpyProductBarcode;

    /**
     * Specification:
     * - Returns ProductBarcode instances filtered by product sku.
     *
     * @api
     *
     * @param string $productSku
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductSku(string $productSku): SpyProductBarcode;

    /**
     * Specification:
     * - Returns all existing ProductBarcode instances from the database.
     *
     * @api
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode[]
     */
    public function getAllProductBarcodes(): array;
}
