<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode\Zed;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\ProductBarcode\Dependency\Client\ProductBarcodeToZedRequestInterface;

class ProductBarcodeStub implements ProductBarcodeStubInterface
{
    /**
     * @var \Spryker\Client\ProductBarcode\Dependency\Client\ProductBarcodeToZedRequestInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ProductBarcode\Dependency\Client\ProductBarcodeToZedRequestInterface $zedRequestClient
     */
    public function __construct(ProductBarcodeToZedRequestInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->zedRequestClient->call('/product-barcode/gateway/generate-barcode', $productConcreteTransfer);
    }
}
