<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductForBundleTransfer;

class ProductBundleMapper
{
    /**
     * @param \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle[] $productBundleEntities
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function mapProductBundleEntitiesToProductForBundleTransfers(
        array $productBundleEntities
    ): array {
        $productForBundleTransfers = [];
        foreach ($productBundleEntities as $productBundleEntity) {
            $productForBundleTransfers[] = (new ProductForBundleTransfer())->fromArray(
                $productBundleEntity->getSpyProductRelatedByFkBundledProduct()->toArray(),
                true
            );
        }

        return $productForBundleTransfers;
    }
}
