<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
class PriceProductEntityManager extends AbstractEntityManager implements PriceProductEntityManagerInterface
{
    /**
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimensionForDelete(
                $priceProductStoreQuery,
                new PriceProductCriteriaTransfer()
            );

        if ($priceProductStoreQuery->getAsColumns() === 0) {
            return;
        }

        $priceProductStoreQuery->find()->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function savePriceProductDefaultEntity(
        SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
    ): SpyPriceProductDefaultEntityTransfer {
        return $this->save($spyPriceProductDefaultEntityTransfer);
    }
}
