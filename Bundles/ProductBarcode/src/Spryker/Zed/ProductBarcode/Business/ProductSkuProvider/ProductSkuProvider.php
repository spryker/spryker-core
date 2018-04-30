<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductSkuProvider;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridgeInterface;

class ProductSkuProvider implements ProductSkuProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridgeInterface
     */
    protected $productBridge;

    /**
     * @param \Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductBridgeInterface $productBridge
     */
    public function __construct(ProductBarcodeToProductBridgeInterface $productBridge)
    {
        $this->productBridge = $productBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function getConcreteProductSku(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $sku = $productConcreteTransfer->getSku();

        if (strlen($sku) > 0) {
            return $sku;
        }

        $idProductConcrete = $productConcreteTransfer->getIdProductConcrete();

        return $this->getConcreteProductSkuFromDatabase($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function getConcreteProductSkuFromDatabase(int $idProductConcrete): string
    {
        return $this->productBridge->findProductConcreteById($idProductConcrete)
            ->getSku();
    }
}
