<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiPersistenceFactory getFactory()
 */
class ProductOfferGuiRepository extends AbstractRepository implements ProductOfferGuiRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    public function findProductAbstractBySku(string $sku): ?SpyProductAbstract
    {
        return $this->getFactory()
            ->getProductAbstractPropelQuery()
            ->filterBySku($sku)
            ->findOne();
    }
}
