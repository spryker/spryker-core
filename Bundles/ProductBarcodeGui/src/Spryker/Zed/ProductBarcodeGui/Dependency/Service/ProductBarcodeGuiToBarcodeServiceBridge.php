<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcodeGui\Dependency\Service;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

class ProductBarcodeGuiToBarcodeServiceBridge implements ProductBarcodeGuiToBarcodeServiceBridgeInterface
{
    /**
     * @var \Spryker\Service\Barcode\BarcodeServiceInterface
     */
    protected $barcodeService;

    /**
     * @param \Spryker\Service\Barcode\BarcodeServiceInterface $barcodeService
     */
    public function __construct($barcodeService)
    {
        $this->barcodeService = $barcodeService;
    }

    /**
     * @param string $text
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(string $text, ?string $generatorPlugin = null): BarcodeResponseTransfer
    {
        return $this->barcodeService->generateBarcode($text, $generatorPlugin);
    }
}
