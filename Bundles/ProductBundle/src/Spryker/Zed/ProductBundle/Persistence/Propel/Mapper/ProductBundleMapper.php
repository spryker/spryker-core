<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductForBundleTransfer;
use Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle;

class ProductBundleMapper
{
    /**
     * @param \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle $productBundleEntity
     * @param \Generated\Shared\Transfer\ProductForBundleTransfer $productForBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer
     */
    public function mapProductBundleEntityToProductForBundleTransfer(
        SpyProductBundle $productBundleEntity,
        ProductForBundleTransfer $productForBundleTransfer
    ): ProductForBundleTransfer {
        return $productForBundleTransfer->fromArray(
            $productBundleEntity->getSpyProductRelatedByFkBundledProduct()->toArray(),
            true
        );
    }
}
