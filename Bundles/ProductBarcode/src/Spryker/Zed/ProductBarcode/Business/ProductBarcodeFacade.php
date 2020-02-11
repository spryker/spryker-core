<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBarcode\Business\ProductBarcodeBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductBarcode\Persistence\ProductBarcodeRepositoryInterface getRepository()
 */
class ProductBarcodeFacade extends AbstractFacade implements ProductBarcodeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(
        ProductConcreteTransfer $productConcreteTransfer,
        ?string $generatorPlugin = null
    ): BarcodeResponseTransfer {
        return $this->getFactory()
            ->createProductBarcodeGenerator()
            ->generateBarcode($productConcreteTransfer, $generatorPlugin);
    }
}
