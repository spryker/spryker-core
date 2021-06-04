<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Exception\PublishAndSynchronizeHealthCheckEntityNotFoundException;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchPersistenceFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckSearchRepository extends AbstractRepository implements PublishAndSynchronizeHealthCheckSearchRepositoryInterface
{
    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @return \Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch
     */
    public function findOrCreatePublishAndSynchronizeHealthCheckSearchByIdPublishAndSynchronizeHealthCheck(
        int $idPublishAndSynchronizeHealthCheck
    ): SpyPublishAndSynchronizeHealthCheckSearch {
        $publishAndSynchronizeHealthCheckSearchEntity = $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckSearchPropelQuery()
            ->filterByFkPublishAndSynchronizeHealthCheck($idPublishAndSynchronizeHealthCheck)
            ->findOneOrCreate();

        return $publishAndSynchronizeHealthCheckSearchEntity;
    }

    /**
     * @param int $idPublishAndSynchronizeHealthCheck
     *
     * @throws \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Exception\PublishAndSynchronizeHealthCheckEntityNotFoundException
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
