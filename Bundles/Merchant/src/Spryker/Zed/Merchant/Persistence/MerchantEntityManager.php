<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantEntityManager extends AbstractEntityManager implements MerchantEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function saveMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantEntity = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($merchantTransfer->getIdMerchant())
            ->findOneOrCreate();

        $merchantEntity = $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantTransferToMerchantEntity($merchantTransfer, $merchantEntity);

        $merchantEntity->save();

        $merchantTransfer = $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($merchantEntity, $merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function createMerchantStore(MerchantTransfer $merchantTransfer, int $idStore): StoreTransfer
    {
        /** @var int $idMerchant */
        $idMerchant = $merchantTransfer->getIdMerchant();

        $merchantStoreEntity = (new SpyMerchantStore())
            ->setFkStore($idStore)
            ->setFkMerchant($idMerchant);

        $merchantStoreEntity->save();

        return $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapStoreEntityToStoreTransfer($merchantStoreEntity->getSpyStore(), new StoreTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param int $idStore
     *
     * @return void
     */
    public function deleteMerchantStore(MerchantTransfer $merchantTransfer, int $idStore): void
    {
        $merchantStoreEntity = $this->getFactory()
            ->createMerchantStoreQuery()
            ->filterByFkMerchant($merchantTransfer->getIdMerchant())
            ->filterByFkStore($idStore)
            ->findOne();

        if ($merchantStoreEntity) {
            $merchantStoreEntity->delete();
        }
    }
}
