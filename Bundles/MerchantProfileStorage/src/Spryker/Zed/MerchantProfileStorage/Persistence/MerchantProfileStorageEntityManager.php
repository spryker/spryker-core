<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function saveMerchantProfileStorageEntity(MerchantProfileStorageTransfer $merchantProfileStorageTransfer): void
    {
        $merchantProfileStorageTransfer->requireFkMerchant();

        $merchantProfileStorageEntity = $this->getFactory()
            ->createMerchantProfileStorageQuery()
            ->filterByFkMerchantProfile($merchantProfileStorageTransfer->getFkMerchantProfile())
            ->findOneOrCreate();
        $merchantProfileStorageEntity = $this->getFactory()
            ->createMerchantProfileStorageMapper()
            ->mapMerchantProfileStorageTransferToMerchantProfileStorageEntity(
                $merchantProfileStorageTransfer,
                $merchantProfileStorageEntity
            );

        $merchantProfileStorageEntity->save();
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return void
     */
    public function deleteMerchantProfileStorageEntitiesByMerchantProfileIds(array $merchantProfileIds): void
    {
        $merchantProfileEntities = $this->getFactory()
            ->createMerchantProfileStorageQuery()
            ->filterByFkMerchantProfile_In($merchantProfileIds)
            ->find();

        foreach ($merchantProfileEntities as $merchantProfileEntity) {
            $merchantProfileEntity->delete();
        }
    }
}
