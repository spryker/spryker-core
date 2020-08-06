<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStoragePersistenceFactory getFactory()
 */
class MerchantOpeningHoursStorageEntityManager extends AbstractEntityManager implements MerchantOpeningHoursStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpenHoursStorageTransfer
     * @param int $fkMerchant
     *
     * @return void
     */
    public function saveMerchantOpenHoursStorage(MerchantOpeningHoursStorageTransfer $merchantOpenHoursStorageTransfer, int $fkMerchant): void
    {
        $merchantOpenHoursStorageEntity = $this->getFactory()
            ->getMerchantOpeningHoursStoragePropelQuery()
            ->filterByFkMerchant($fkMerchant)
            ->findOneOrCreate();

        $merchantOpenHoursStorageEntity
            ->setData($merchantOpenHoursStorageTransfer->toArray())
            ->setFkMerchant($fkMerchant)
            ->save();
    }
}
