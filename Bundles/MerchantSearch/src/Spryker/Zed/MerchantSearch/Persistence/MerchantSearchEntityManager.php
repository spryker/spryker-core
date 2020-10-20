<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\MerchantSearchTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return void
     */
    public function saveMerchantSearch(MerchantSearchTransfer $merchantSearchTransfer): void
    {
        /** @var \Orm\Zed\MerchantSearch\Persistence\Base\SpyMerchantSearch $merchantSearchEntity */
        $merchantSearchEntity = $this->getFactory()
            ->getMerchantSearchPropelQuery()
            ->filterByFkMerchant($merchantSearchTransfer->getIdMerchant())
            ->findOneOrCreate();

        $k = $merchantSearchTransfer->toArray();
        $merchantSearchEntity->fromArray(
            $merchantSearchTransfer->toArray()
        );
        $merchantSearchEntity->setFkMerchant($merchantSearchTransfer->getIdMerchant());
        $merchantSearchEntity->save();
    }
}
