<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Dependency\Facade;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductBarcodeGuiToProductBarcodeFacadeBridge implements ProductBarcodeGuiToProductBarcodeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface
     */
    protected $productBarcodeFacade;

    /**
     * @param \Spryker\Zed\ProductBarcode\Business\ProductBarcodeFacadeInterface $productBarcodeFacade
     */
    public function __construct($productBarcodeFacade)
    {
        $this->productBarcodeFacade = $productBarcodeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(
        ProductConcreteTransfer $productConcreteTransfer,
        ?string $generatorPlugin = null
    ): BarcodeResponseTransfer {
        return $this->productBarcodeFacade->generateBarcode($productConcreteTransfer, $generatorPlugin);
    }
}
