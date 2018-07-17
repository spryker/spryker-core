<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PriceProductVolume\Persistence\PriceProductVolumePersistenceFactory getFactory()
 */
class PriceProductVolumeRepository extends AbstractRepository implements PriceProductVolumeRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int|null
     */
    public function findIdProductAbstractForPriceProduct(PriceProductTransfer $priceProductTransfer): ?int
    {
        if ($priceProductTransfer->getIdProduct()) {
            $productEntity = $this->getProductQuery()
                ->findOneByIdProduct($priceProductTransfer->getIdProduct());

            return $productEntity ? $productEntity->getFkProductAbstract() : null;
        }

        if ($priceProductTransfer->getSkuProduct()) {
            $productEntity = $this->getProductQuery()
                ->findOneBySku($priceProductTransfer->getSkuProduct());

            return $productEntity ? $productEntity->getFkProductAbstract() : null;
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
