<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence;

use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductFilterCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStoragePersistenceFactory getFactory()
 */
class MerchantProductStorageRepository extends AbstractRepository implements MerchantProductStorageRepositoryInterface
{
    /**
     * @param int[] $merchantProductAbstractIds
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function getMerchantProducts(array $merchantProductAbstractIds): MerchantProductCollectionTransfer
    {
        $merchantProductEntities = $this->getFactory()
            ->getMerchantProductAbstractPropelQuery()
            ->joinWithMerchant()
            ->joinWithProductAbstract()
            ->filterByIdProductAbstractMerchant_In($merchantProductAbstractIds)
            ->find();

        $merchantProductCollectionTransfer = new MerchantProductCollectionTransfer();
        $merchantProductStorageMapper = $this->getFactory()->createMerchantProductStorageMapper();
        foreach ($merchantProductEntities as $merchantProductEntity) {
            $merchantProductCollectionTransfer->addMerchantProduct(
                $merchantProductStorageMapper->mapMerchantProductEntityToMerchantProductTransfer(
                    $merchantProductEntity,
                    new MerchantProductTransfer()
                )
            );
        }

        return $merchantProductCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductFilterCriteriaTransfer $merchantProductFilterCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorage[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getMerchantProductStorageEntitiesByFilterCriteria(
        MerchantProductFilterCriteriaTransfer $merchantProductFilterCriteriaTransfer
    ): ObjectCollection {
        $merchantProductStoragePropelQuery = $this->getFactory()->createMerchantProductStoragePropelQuery();

        if ($merchantProductFilterCriteriaTransfer->getIdProductAbstractMerchantStorages()) {
            $merchantProductStoragePropelQuery->filterByIdProductAbstractMerchantStorage_In(
                $merchantProductFilterCriteriaTransfer->getIdProductAbstractMerchantStorages()
            );
        }

        $merchantProductStoragePropelQuery->offset($merchantProductFilterCriteriaTransfer->getOffset());
        $merchantProductStoragePropelQuery->limit($merchantProductFilterCriteriaTransfer->getLimit());

        return $merchantProductStoragePropelQuery->find();
    }
}
