<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Persistence;

use Generated\Shared\Transfer\ProductBundleTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundlePersistenceFactory getFactory()
 */
class ProductBundleRepository extends AbstractRepository implements ProductBundleRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductBundleTransfer
     */
    public function findBundledProducts(string $sku): ProductBundleTransfer
    {
        $bundledProducts = $this->getFactory()
            ->createProductBundleQuery()
            ->useSpyProductRelatedByFkProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        return $this->getFactory()
            ->createProductBundleMapper()
            ->mapProductBundleTransfer($bundledProducts->toArray());
    }
}
