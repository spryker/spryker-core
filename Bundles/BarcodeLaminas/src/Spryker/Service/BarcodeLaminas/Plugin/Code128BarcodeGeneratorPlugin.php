<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas\Plugin;

use Generated\Shared\Transfer\BarcodeRequestTransfer;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\BarcodeExtension\Dependency\Plugin\BarcodeGeneratorPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * Provides ability to generate barcodes using Laminas barcode library.
 *
 * @method \Spryker\Service\BarcodeLaminas\BarcodeLaminasServiceInterface getService()
 */
class Code128BarcodeGeneratorPlugin extends AbstractPlugin implements BarcodeGeneratorPluginInterface
{
    /**
     * @var string
     */
    protected const BARCODE_TYPE = 'code128';

    /**
     * @var string
     */
    protected const RENDERER_IMAGE = 'image';

    /**
     * {@inheritDoc}
     * - Generates a barcode based on the given text using Laminas barcode library.
     * - Returns BarcodeResponseTransfer with encoding (like base64) and encoded string that represents the barcode.
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer
    {
        $barcodeRequestTransfer = (new BarcodeRequestTransfer())
            ->setText($text)
            ->setBarcodeType(static::BARCODE_TYPE)
            ->setRenderer(static::RENDERER_IMAGE);

        return $this->getService()->generateBarcode($barcodeRequestTransfer);
    }
}
