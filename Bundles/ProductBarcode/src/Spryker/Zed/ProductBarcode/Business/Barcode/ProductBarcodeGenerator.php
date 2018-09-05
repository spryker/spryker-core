<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business\Barcode;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBarcode\Business\Product\ProductSkuProviderInterface;
use Spryker\Zed\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceInterface;

class ProductBarcodeGenerator implements ProductBarcodeGeneratorInterface
{
    /**
     * @var \Spryker\Zed\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceInterface
     */
    protected $barcodeService;

    /**
     * @var \Spryker\Zed\ProductBarcode\Business\Product\ProductSkuProviderInterface
     */
    protected $productSkuProvider;

    /**
     * @param \Spryker\Zed\ProductBarcode\Dependency\Service\ProductBarcodeToBarcodeServiceInterface $barcodeService
     * @param \Spryker\Zed\ProductBarcode\Business\Product\ProductSkuProviderInterface $stockCodeSelector
     */
    public function __construct(
        ProductBarcodeToBarcodeServiceInterface $barcodeService,
        ProductSkuProviderInterface $stockCodeSelector
    ) {
        $this->barcodeService = $barcodeService;
        $this->productSkuProvider = $stockCodeSelector;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, ?string $generatorPlugin): BarcodeResponseTransfer
    {
        $code = $this
            ->productSkuProvider
            ->getConcreteProductSku($productConcreteTransfer);

        return $this->barcodeService->generateBarcode($code, $generatorPlugin);
    }
}
