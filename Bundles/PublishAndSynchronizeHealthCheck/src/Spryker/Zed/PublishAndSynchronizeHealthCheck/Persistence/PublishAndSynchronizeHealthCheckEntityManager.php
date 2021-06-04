<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckPersistenceFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckEntityManager extends AbstractEntityManager implements PublishAndSynchronizeHealthCheckEntityManagerInterface
{
    /**
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function upsertPublishAndSynchronizeHealthCheckEntity(): PublishAndSynchronizeHealthCheckTransfer
    {
        $publishAndSynchronizeHealthCheckEntity = $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckQuery()
            ->filterByHealthCheckKey(PublishAndSynchronizeHealthCheckConfig::DEFAULT_HEALTH_CHECK_KEY)
            ->findOneOrCreate();

        $publishAndSynchronizeHealthCheckEntity->setHealthCheckData(uniqid());

        $publishAndSynchronizeHealthCheckEntity->save();

        $publishAndSynchronizeHealthCheckTransfer = new PublishAndSynchronizeHealthCheckTransfer();
        $publishAndSynchronizeHealthCheckTransfer->fromArray($publishAndSynchronizeHealthCheckEntity->toArray(), true);

        return $publishAndSynchronizeHealthCheckTransfer;
    }
}
