<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductAlternative\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductAlternativeDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param string $skuProductAlternative
     *
     * @return void
     */
    public function haveProductAlternative(
        ProductConcreteTransfer $productConcreteTransfer,
        string $skuProductAlternative
    ): void {
        $productAlternativeCreateRequestTransfer = $this->createProductAlternativeCreateRequestTransfer(
            $productConcreteTransfer->getIdProductConcrete(),
            $skuProductAlternative
        );
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $this->getLocator()->productAlternative()->facade()
            ->persistProductAlternative($productConcreteTransfer);
    }

    /**
     * @param int $idProductConcrete
     * @param string $skuProductAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer
     */
    protected function createProductAlternativeCreateRequestTransfer(
        int $idProductConcrete,
        string $skuProductAlternative
    ): ProductAlternativeCreateRequestTransfer {
        return (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($idProductConcrete)
            ->setAlternativeSku($skuProductAlternative);
    }
}
