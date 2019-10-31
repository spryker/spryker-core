<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStoragePersistenceFactory getFactory()
 */
class MerchantProfileStorageEntityManager extends AbstractEntityManager implements MerchantProfileStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return void
     */
    public function saveMerchantProfileStorage(MerchantProfileTransfer $merchantProfileTransfer): void
    {
        $merchantProfileTransfer->requireIdMerchantProfile();

        $merchantProfileStorageEntity = $this->getFactory()
            ->createMerchantProfileStorageQuery()
            ->filterByFkMerchant($merchantProfileTransfer->getFkMerchant())
            ->findOneOrCreate();

        $merchantProfileStorageTransfer = new MerchantProfileStorageTransfer();
        $merchantProfileStorageTransfer->fromArray($merchantProfileStorageEntity->toArray(), true);

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
