<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductPersistenceFactory getFactory()
 */
class MerchantProductRepository extends AbstractRepository implements MerchantProductRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer
    {
        $merchantProductAbstractQuery = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->joinWithMerchant();

        $merchantProductEntity = $this->applyFilters($merchantProductAbstractQuery, $merchantProductCriteriaTransfer)
            ->findOne();

        if (!$merchantProductEntity) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($merchantProductEntity->getMerchant(), new MerchantTransfer());
    }

    /**
     * @param int[] $idProductAbstractMerchants
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer[]
     */
    public function findMerchantProducts(array $idProductAbstractMerchants): array
    {
        $merchantProductEntities = $this->getFactory()->getMerchantProductAbstractPropelQuery()
            ->filterByIdProductAbstractMerchant_In($idProductAbstractMerchants)
            ->find();

        $merchantProductTransfers = [];
        $merchantProductMapper = $this->getFactory()->createMerchantProductMapper();

        foreach ($merchantProductEntities as $merchantProductEntity) {
            $merchantProductTransfers[] = $merchantProductMapper
                ->mapMerchantProductEntityToMerchantProductTransfer($merchantProductEntity, new MerchantProductTransfer());
        }

        return $merchantProductTransfers;
    }

    /**
     * @param \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery $merchantProductAbstractQuery
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    protected function applyFilters(
        SpyMerchantProductAbstractQuery $merchantProductAbstractQuery,
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): SpyMerchantProductAbstractQuery {
        if ($merchantProductCriteriaTransfer->getIdProductAbstract()) {
            $merchantProductAbstractQuery->filterByFkProductAbstract($merchantProductCriteriaTransfer->getIdProductAbstract());
        }

        return $merchantProductAbstractQuery;
    }
}
