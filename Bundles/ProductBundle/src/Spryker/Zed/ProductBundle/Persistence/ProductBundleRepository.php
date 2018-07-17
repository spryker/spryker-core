<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer[]
     */
    public function findBundledProductsBySku(string $sku): array
    {
        $productBundleEntities = $this->getFactory()
            ->createProductBundleQuery()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        $productEntityTransferList = [];

        foreach ($productBundleEntities as $productBundleEntity) {
            $productEntityTransfer = new SpyProductEntityTransfer();
            $productEntityTransfer->fromArray($productBundleEntity->getSpyProductRelatedByFkBundledProduct()->toArray(), true);

            $productEntityTransferList[] = $productEntityTransfer;
        }

        return $productEntityTransferList;
    }
}
