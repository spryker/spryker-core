<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas\BarcodeGenerator;

use Generated\Shared\Transfer\BarcodeRequestTransfer;
use Generated\Shared\Transfer\BarcodeResponseTransfer;

interface BarcodeGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\BarcodeRequestTransfer $barcodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(BarcodeRequestTransfer $barcodeRequestTransfer): BarcodeResponseTransfer;
}
