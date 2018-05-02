<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBarcode\Business\ProductBarcodeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeQueryContainerInterface getQueryContainer()
 */
class ProductBarcodeFacade extends AbstractFacade implements ProductBarcodeFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this->getFactory()
            ->createProductBarcodeGenerator()
            ->generateBarcode($productConcreteTransfer, $generatorPlugin);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $productId
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductId(int $productId): SpyProductBarcode
    {
        return $this->getFactory()
            ->createProductBarcodeFinder()
            ->findProductBarcodeByProductId($productId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $productName
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductName(string $productName): SpyProductBarcode
    {
        return $this->getFactory()
            ->createProductBarcodeFinder()
            ->findProductBarcodeByProductName($productName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $productSku
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode
     */
    public function findProductBarcodeByProductSku(string $productSku): SpyProductBarcode
    {
        return $this->getFactory()
            ->createProductBarcodeFinder()
            ->findProductBarcodeByProductSku($productSku);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductBarcode\Persistence\SpyProductBarcode[]
     */
    public function getAllProductBarcodes(): array
    {
        return $this->getFactory()
            ->createProductBarcodeFinder()
            ->getAllProductBarcodes();
    }
}
