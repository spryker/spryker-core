<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\ProductBarcodeGenerator;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Service\Barcode\BarcodeServiceInterface;
use Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProviderInterface;

class ProductBarcodeGenerator implements ProductBarcodeGeneratorInterface
{
    /**
     * @var \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    protected $barcodeService;

    /**
     * @var \Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProviderInterface
     */
    protected $productSkuProvider;

    /**
     * @param \Spryker\Service\Barcode\BarcodeServiceInterface $barcodeService
     * @param \Spryker\Zed\ProductBarcode\Business\ProductSkuProvider\ProductSkuProviderInterface $stockCodeSelector
     */
    public function __construct(BarcodeServiceInterface $barcodeService, ProductSkuProviderInterface $stockCodeSelector)
    {
        $this->barcodeService = $barcodeService;
        $this->productSkuProvider = $stockCodeSelector;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param null|string $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, ?string $generatorPlugin): BarcodeResponseTransfer
    {
        $code = $this->productSkuProvider->getConcreteProductSku($productConcreteTransfer);

        return $this->barcodeService->generateBarcode($code, $generatorPlugin);
    }
}
