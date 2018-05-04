<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CodeItNow;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface CodeItNowServiceInterface
{
    /**
     * Specification:
     * - Generates a Code128 barcode based on the given $text.
     *
     * @api
     *
     * @param string $text
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateCode128Barcode(string $text): BarcodeResponseTransfer;
}
