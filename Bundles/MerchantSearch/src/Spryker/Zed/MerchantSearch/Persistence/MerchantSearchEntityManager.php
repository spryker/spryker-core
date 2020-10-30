<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchPersistenceFactory getFactory()
 */
class MerchantSearchEntityManager extends AbstractEntityManager implements MerchantSearchEntityManagerInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function deleteMerchantSearchByMerchantIds(array $merchantIds): void
    {
        $this->getFactory()
            ->getMerchantSearchPropelQuery()
            ->filterByFkMerchant_In($merchantIds)
            ->find()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return void
     */
    public function saveMerchantSearches(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): void
    {
        $merchantSearchEntityCollection = new ObjectCollection();
        $merchantSearchEntityCollection->setModel(SpyMerchantSearch::class);

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantSearchEntityCollection->append($this->getMerchantSearchEntityByMerchantSearchTransfer($merchantSearchTransfer));
        }

        $merchantSearchEntityCollection->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return \Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch
     */
    protected function getMerchantSearchEntityByMerchantSearchTransfer(
        MerchantSearchTransfer $merchantSearchTransfer
    ): SpyMerchantSearch {
        $merchantSearchEntity = $this->getFactory()
            ->getMerchantSearchPropelQuery()
            ->filterByFkMerchant($merchantSearchTransfer->getIdMerchant())
            ->findOneOrCreate();

        $merchantSearchEntity->fromArray(
            $merchantSearchTransfer->toArray()
        );
        $merchantSearchEntity->setFkMerchant($merchantSearchTransfer->getIdMerchant());

        return $merchantSearchEntity;
    }
}
