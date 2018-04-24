<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBarcode\Business;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

/**
 * @method \Spryker\Zed\ProductBarcode\Business\ProductBarcodeBusinessFactory getFactory()
 */
class ProductBarcodeFacade implements ProductBarcodeFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string|null $generatorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcode(ProductConcreteTransfer $productConcreteTransfer, string $generatorPlugin = null): BarcodeResponseTransfer
    {
        $resolver = $this->getFactory()
            ->createProductBarcodeNumberResolver();

        $text = $resolver->resolve($productConcreteTransfer);

        $this->getFactory()
            ->getBarcodeGeneratorService()
            ->generateBarcode($text, $generatorPlugin);
    }
}
