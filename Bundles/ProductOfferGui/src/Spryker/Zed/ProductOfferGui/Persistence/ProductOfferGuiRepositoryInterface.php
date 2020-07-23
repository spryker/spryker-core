<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstract;

interface ProductOfferGuiRepositoryInterface
{
    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    public function findProductAbstractBySku(string $sku): ?SpyProductAbstract;
}
