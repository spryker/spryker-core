<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductBundleBuilder;
use Generated\Shared\DataBuilder\ProductForBundleBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductBundleHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $productBundleOverride
     * @param array $bundledProductsOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductBundle(
        ProductConcreteTransfer $productConcreteTransfer,
        array $productBundleOverride = [],
        array $bundledProductsOverride = []
    ): ProductConcreteTransfer {
        /** @var \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer */
        $productBundleTransfer = (new ProductBundleBuilder())
            ->seed($productBundleOverride)
            ->build();

        foreach ($bundledProductsOverride as $bundledProductOverride) {
            $bundledProductTransfer = (new ProductForBundleBuilder())
                ->seed($bundledProductOverride)
                ->build();

            $productBundleTransfer->addBundledProduct($bundledProductTransfer);
        }

        $productConcreteTransfer = $this->getProductBundleFacade()->saveBundledProducts(
            $productConcreteTransfer->setProductBundle($productBundleTransfer)
        );

        $productBundleTransfer->setIdProductConcreteBundle($productConcreteTransfer->getIdProductConcrete());

        return $productConcreteTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    public function getProductBundleFacade(): ProductBundleFacadeInterface
    {
        return $this->getLocator()->productBundle()->facade();
    }
}
