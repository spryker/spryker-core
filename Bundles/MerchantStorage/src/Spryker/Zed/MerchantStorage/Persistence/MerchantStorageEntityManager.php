<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStoragePersistenceFactory getFactory()
 */
class MerchantStorageEntityManager extends AbstractEntityManager implements MerchantStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function saveMerchantStorage(MerchantStorageTransfer $merchantStorageTransfer): MerchantStorageTransfer
    {
        $merchantStorageEntity = $this->getFactory()
            ->createMerchantStorageQuery()
            ->filterByIdMerchant($merchantStorageTransfer->getIdMerchant())
            ->findOneOrCreate();

        $merchantStorageEntity->setData($merchantStorageTransfer->toArray());
        $merchantStorageEntity->save();

        return $this->getFactory()
            ->createMerchantStorageMapper()
            ->mapMerchantStorageEntityToMerchantStorageTransfer($merchantStorageEntity, new MerchantStorageTransfer());
    }
}
