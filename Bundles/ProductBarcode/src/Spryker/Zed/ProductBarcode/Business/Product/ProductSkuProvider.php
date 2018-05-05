<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductFacadeInterface;

class ProductSkuProvider implements ProductSkuProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductBarcode\Dependency\Facade\ProductBarcodeToProductFacadeInterface $productBridge
     */
    public function __construct(ProductBarcodeToProductFacadeInterface $productBridge)
    {
        $this->productFacade = $productBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function getConcreteProductSku(ProductConcreteTransfer $productConcreteTransfer): string
    {
        $sku = $productConcreteTransfer->getSku();

        if ($sku && strlen($sku) > 0) {
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
        return $this->productFacade->findProductConcreteById($idProductConcrete)
            ->getSku();
    }
}
