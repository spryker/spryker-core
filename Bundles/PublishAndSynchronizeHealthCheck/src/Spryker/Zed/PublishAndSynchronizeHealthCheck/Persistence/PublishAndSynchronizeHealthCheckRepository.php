<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckPersistenceFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckRepository extends AbstractRepository implements PublishAndSynchronizeHealthCheckRepositoryInterface
{
    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer|null
     */
    public function findPublishAndSynchronizeHealthCheckByKey(string $key): ?PublishAndSynchronizeHealthCheckTransfer
    {
        $publishAndSynchronizeHealthCheckEntity = $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckQuery()
            ->filterByHealthCheckKey($key)
            ->findOne();

        if (!$publishAndSynchronizeHealthCheckEntity) {
            return null;
        }

        return (new PublishAndSynchronizeHealthCheckTransfer())->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true);
    }
}
