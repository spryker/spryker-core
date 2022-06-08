<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeRequestTransfer;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Laminas\Barcode\Barcode;
use Laminas\Barcode\Renderer\RendererInterface;

class BarcodeGenerator implements BarcodeGeneratorInterface
{
    /**
     * @var string
     */
    protected const ENCODING = 'data:image/png;base64';

    /**
     * @param \Generated\Shared\Transfer\BarcodeRequestTransfer $barcodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(BarcodeRequestTransfer $barcodeRequestTransfer): BarcodeResponseTransfer
    {
        $renderer = $this->createRenderer($barcodeRequestTransfer);

        ob_start();
        $renderer->render();

        /** @var string $contents */
        $contents = ob_get_clean();

        return $this->createBarcodeResponseTransfer(base64_encode($contents));
    }

    /**
     * @param \Generated\Shared\Transfer\BarcodeRequestTransfer $barcodeRequestTransfer
     *
     * @return \Laminas\Barcode\Renderer\RendererInterface
     */
    protected function createRenderer(BarcodeRequestTransfer $barcodeRequestTransfer): RendererInterface
    {
        $options = $barcodeRequestTransfer->getBarcodeOptions();
        $options['text'] = $barcodeRequestTransfer->getTextOrFail();

        return Barcode::factory(
            $barcodeRequestTransfer->getBarcodeTypeOrFail(),
            $barcodeRequestTransfer->getRendererOrFail(),
            $options,
        );
    }

    /**
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function createBarcodeResponseTransfer(string $text): BarcodeResponseTransfer
    {
        return (new BarcodeResponseTransfer())
            ->setCode($text)
            ->setEncoding(static::ENCODING);
    }
}
