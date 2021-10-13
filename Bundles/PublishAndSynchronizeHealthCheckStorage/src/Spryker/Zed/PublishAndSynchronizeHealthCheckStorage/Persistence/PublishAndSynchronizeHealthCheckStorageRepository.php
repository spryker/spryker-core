<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorage;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Exception\PublishAndSynchronizeHealthCheckEntityNotFoundException;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStoragePersistenceFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckStorageRepository extends AbstractRepository implements PublishAndSynchronizeHealthCheckStorageRepositoryInterface
{
    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorage
     */
    public function findOrCreatePublishAndSynchronizeHealthCheckStorageByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): SpyPublishAndSynchronizeHealthCheckStorage {
        $publishAndSynchronizeHealthCheckStorageEntity = $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckStoragePropelQuery()
            ->filterByFkPublishAndSynchronizeHealthCheck($idPublishAndSynchronizeHealthCheck)
            ->findOneOrCreate();

        return $publishAndSynchronizeHealthCheckStorageEntity;
    }

    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @throws \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Exception\PublishAndSynchronizeHealthCheckEntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function getPublishAndSynchronizeHealthCheckTransferByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): PublishAndSynchronizeHealthCheckTransfer {
        $publishAndSynchronizeHealthCheckEntity = $this->getFactory()
            ->getPublishAndSynchronizeHealthCheckPropelQuery()
            ->filterByIdPublishAndSynchronizeHealthCheck($idPublishAndSynchronizeHealthCheck)
            ->findOne();

        if (!$publishAndSynchronizeHealthCheckEntity) {
            throw new PublishAndSynchronizeHealthCheckEntityNotFoundException();
        }

        $publishAndSynchronizeHealthCheckTransfer = new PublishAndSynchronizeHealthCheckTransfer();
        $publishAndSynchronizeHealthCheckTransfer->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true);

        return $publishAndSynchronizeHealthCheckTransfer;
    }
}
