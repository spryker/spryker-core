<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Persistence;

use Generated\Shared\Transfer\MerchantProductStorageTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStoragePersistenceFactory getFactory()
 */
class MerchantProductStorageEntityManager extends AbstractEntityManager implements MerchantProductStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return void
     */
    public function saveMerchantProductStorage(MerchantProductTransfer $merchantProductTransfer): void
    {
        $merchantProductStorageEntity = $this->getFactory()
            ->createMerchantProductStoragePropelQuery()
            ->filterByFkProductAbstract($merchantProductTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $merchantProductStorageTransfer = $this->getFactory()
            ->createMerchantProductStorageMapper()
            ->mapMerchantProductTransferToMerchantProductStorageTransfer(
                $merchantProductTransfer,
                new MerchantProductStorageTransfer()
            );

        $merchantProductStorageEntity->setData($merchantProductStorageTransfer->toArray());
        $merchantProductStorageEntity->save();
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    public function deleteMerchantProductStorageEntitiesByIdProductAbstracts(array $idProductAbstracts): void
    {
        $this->getFactory()->createMerchantProductStoragePropelQuery()
            ->filterByFkProductAbstract_In($idProductAbstracts)
            ->deleteAll();
    }
}
