<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Orm\Zed\MerchantSearch\Persistence\SpyMerchantSearch;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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
    public function saveCollection(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): void
    {
        $merchantSearchTransferIdMerchantMap = [];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            $merchantSearchTransferIdMerchantMap[$merchantSearchTransfer->getIdMerchant()] = $merchantSearchTransfer;
        }

        $merchantSearchEntityCollection = $this->getFactory()
            ->getMerchantSearchPropelQuery()
            ->filterByFkMerchant(array_keys($merchantSearchTransferIdMerchantMap), Criteria::IN)
            ->find();

        $merchantSearchMapper = $this->getFactory()->createMerchantSearchMapper();

        foreach ($merchantSearchEntityCollection as $merchantSearchEntity) {
            $merchantSearchEntity = $merchantSearchMapper->mapMerchantSearchTransferToMerchantSearchEntity(
                $merchantSearchTransferIdMerchantMap[$merchantSearchEntity->getFkMerchant()],
                $merchantSearchEntity
            );

            unset($merchantSearchTransferIdMerchantMap[$merchantSearchEntity->getFkMerchant()]);
        }

        foreach ($merchantSearchTransferIdMerchantMap as $merchantSearchTransfer) {
            $merchantSearchEntity = $merchantSearchMapper->mapMerchantSearchTransferToMerchantSearchEntity(
                $merchantSearchTransfer,
                new SpyMerchantSearch()
            );

            $merchantSearchEntityCollection->append($merchantSearchEntity);
        }

        $merchantSearchEntityCollection->save();
    }
}
