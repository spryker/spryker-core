<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStoragePersistenceFactory getFactory()
 */
class MerchantProfileStorageEntityManager extends AbstractEntityManager implements MerchantProfileStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileStorageTransfer $merchantProfileStorageTransfer
     *
     * @return void
     */
    public function saveMerchantProfileStorage(MerchantProfileStorageTransfer $merchantProfileStorageTransfer): void
    {
        $merchantProfileStorageEntity = $this->getFactory()
            ->createMerchantProfileStorageQuery()
            ->filterByFkMerchant($merchantProfileStorageTransfer->getFkMerchant())
            ->findOneOrCreate();

        $merchantProfileStorageEntity->setData($merchantProfileStorageTransfer->toArray());
        $merchantProfileStorageEntity->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue());

        $merchantProfileStorageEntity->save();
    }

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function deleteMerchantProfileStorageEntitiesByMerchantIds(array $merchantIds): void
    {
        $merchantProfileEntities = $this->getFactory()
            ->createMerchantProfileStorageQuery()
            ->filterByFkMerchant_In($merchantIds)
            ->find();

        foreach ($merchantProfileEntities as $merchantProfileEntity) {
            $merchantProfileEntity->delete();
        }
    }
}
