<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\PublishAndSynchronizeHealthCheckStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface getRepository()
 */
class PublishAndSynchronizeHealthCheckStorageFacade extends AbstractFacade implements PublishAndSynchronizeHealthCheckStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckStorageWriter()
            ->writePublishAndSynchronizeHealthCheckStorageCollectionByPublishAndSynchronizeHealthCheckEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function performHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createHealthCheck()->performHealthCheck();
    }
}
