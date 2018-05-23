<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductBarcode\ProductBarcodeFactory getFactory()
 */
class ProductBarcodeClient extends AbstractClient implements ProductBarcodeClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(
        ProductConcreteTransfer $productConcreteTransfer
    ): BarcodeResponseTransfer {
        return $this->getFactory()
            ->createProductBarcodeStub()
            ->generateBarcode($productConcreteTransfer);
    }
}
