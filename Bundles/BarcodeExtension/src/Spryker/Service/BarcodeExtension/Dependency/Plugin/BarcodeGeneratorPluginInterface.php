<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeExtension\Dependency\Plugin;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface BarcodeGeneratorPluginInterface
{
    /**
     * Specification:
     * - Generates image of barcode by provided $text
     * - Every plugin should implement one coding standard (like EAN-13) and one image style (size, fonts e.t.c.)
     * - Image passed as encoded string inside BarcodeResponseTransfer
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generate(string $text): BarcodeResponseTransfer;
}
