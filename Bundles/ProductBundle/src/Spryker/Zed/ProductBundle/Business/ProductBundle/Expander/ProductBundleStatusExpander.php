<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;

class ProductBundleStatusExpander implements ProductBundleStatusExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected $productBundleReader;

    /**
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface $productBundleReader
     */
    public function __construct(ProductBundleReaderInterface $productBundleReader)
    {
        $this->productBundleReader = $productBundleReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductBundleStatusByBundledProductStatuses(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        if ($productConcreteTransfer->getProductBundle() === null) {
            return $productConcreteTransfer;
        }

        $productForBundleTransfer = $this->productBundleReader
            ->findBundledProductsByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
        foreach ($productForBundleTransfer as $bundledProductTransfer) {
            if (!$bundledProductTransfer->getIsActive()) {
                return $productConcreteTransfer->setIsActive(false);
            }
        }

        return $productConcreteTransfer;
    }
}
