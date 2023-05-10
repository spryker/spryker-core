<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Persistence;

use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStoragePersistenceFactory getFactory()
 */
class ServicePointStorageEntityManager extends AbstractEntityManager implements ServicePointStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageTransfer $servicePointStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveServicePointStorageForStore(
        ServicePointStorageTransfer $servicePointStorageTransfer,
        string $storeName
    ): void {
        $servicePointStorageEntity = $this->getFactory()
            ->getServicePointStorageQuery()
            ->filterByFkServicePoint($servicePointStorageTransfer->getIdServicePointOrFail())
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $servicePointStorageEntity = $this->getFactory()
            ->createServicePointStorageMapper()
            ->mapServicePointStorageTransferToServicePointStorageEntity($servicePointStorageTransfer, $servicePointStorageEntity);

        $servicePointStorageEntity->save();
    }

    /**
     * @param list<int> $servicePointIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteServicePointStorageByServicePointIds(array $servicePointIds, ?string $storeName = null): void
    {
        $servicePointQuery = $this->getFactory()
            ->getServicePointStorageQuery()
            ->filterByFkServicePoint_In($servicePointIds);

        if ($storeName) {
            $servicePointQuery->filterByStore($storeName);
        }

        $servicePointQuery->find()->delete();
    }
}
