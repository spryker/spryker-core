<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\BarcodeLaminas;

use Generated\Shared\Transfer\BarcodeRequestTransfer;
use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\BarcodeLaminas\BarcodeLaminasServiceFactory getFactory()
 */
class BarcodeLaminasService extends AbstractService implements BarcodeLaminasServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\BarcodeRequestTransfer $barcodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(BarcodeRequestTransfer $barcodeRequestTransfer): BarcodeResponseTransfer
    {
        return $this->getFactory()
            ->createBarcodeGenerator()
            ->generateBarcode($barcodeRequestTransfer);
    }
}
