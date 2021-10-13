<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\PublishAndSynchronizeHealthCheckSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchEntityManagerInterface getEntityManager()
 */
class PublishAndSynchronizeHealthCheckSearchFacade extends AbstractFacade implements PublishAndSynchronizeHealthCheckSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createPublishAndSynchronizeHealthCheckSearchWriter()
            ->writePublishAndSynchronizeHealthCheckSearchCollectionByPublishAndSynchronizeHealthCheckEvents($eventTransfers);
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
