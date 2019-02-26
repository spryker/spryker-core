<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductForBundleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsBySku(string $sku): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->joinWithSpyProductRelatedByFkProduct()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        $productForBundleTransfers = [];

        /** @var \Orm\Zed\ProductBundle\Persistence\Base\SpyProductBundle $productBundleEntity */
        foreach ($productBundleEntities as $productBundleEntity) {
            $productForBundleTransfers[] = $this->getFactory()
                ->createProductBundleMapper()
                ->mapProductBundleEntityToProductForBundleTransfer(
                    $productBundleEntity,
                    new ProductForBundleTransfer()
                );
        }

        return $productForBundleTransfers;
    }
}
