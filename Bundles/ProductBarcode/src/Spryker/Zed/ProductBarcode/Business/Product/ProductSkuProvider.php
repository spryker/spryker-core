<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeRepositoryInterface;

class ProductSkuProvider implements ProductSkuProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeRepositoryInterface
     */
    protected $productBarcodeRepository;

    /**
     * @param \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeRepositoryInterface $productBarcodeRepository
     */
    public function __construct(ProductBarcodeRepositoryInterface $productBarcodeRepository)
    {
        $this->productBarcodeRepository = $productBarcodeRepository;
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

        $idProductConcrete = $productConcreteTransfer
            ->requireIdProductConcrete()
            ->getIdProductConcrete();

        return $this->getConcreteProductSkuFromDatabase($idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function getConcreteProductSkuFromDatabase(int $idProductConcrete): string
    {
        return $this
            ->productBarcodeRepository
            ->getProductById($idProductConcrete)
            ->getSku();
    }
}
