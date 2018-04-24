<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\BarcodeExtension\Service\Dependency\Plugin;

interface BarcodeGeneratorPluginInterface
{
    /**
     * Specification:
     * - Generate image of barcode by provided $text
     * - Every plugin should implement one coding standard (like EAN-13) and one image style (size, fonts e.t.c.)
     * - Image passed as encoded string inside BarcodeResponseTransfer
     *
     * @api
     *
     * @param string $text
     * @return string
     */
    public function generate(string $text): string;
}
