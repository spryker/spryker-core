<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;

class ProductBundleMapper
{
    /**
     * @param array $bundledProducts
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer
     */
    public function mapProductBundleTransfer(array $bundledProducts): ProductBundleTransfer
    {
        $productBundleTransfer = new ProductBundleTransfer();
        foreach ($bundledProducts as $bundledProduct) {
            $productBundleTransfer->addBundledProduct(
                (new ProductForBundleTransfer())
                    ->fromArray($bundledProduct->getSpyProductRelatedByFkBundledProduct()->toArray(), true)
            );
        }

        return $productBundleTransfer;
    }
}
