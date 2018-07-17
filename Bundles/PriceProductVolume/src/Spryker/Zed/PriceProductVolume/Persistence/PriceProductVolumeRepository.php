<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

class PriceProductVolumeRepository extends AbstractRepository implements PriceProductVolumeRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdProductAbstractForPriceProduct(PriceProductTransfer $priceProductTransfer): ?int
    {
        $query = $this->getProductQuery()
            ->select([SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT]);

        if ($priceProductTransfer->getIdProduct()) {
            return $query->findOneByIdProduct($priceProductTransfer->getIdProduct());
        }

        if ($priceProductTransfer->getSkuProduct()) {
            return $query->findOneBySku($priceProductTransfer->getSkuProduct());
        }

        return null;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function getProductQuery(): SpyProductQuery
    {
        return $this->getFactory()->getPropelProductQuery();
    }
}
