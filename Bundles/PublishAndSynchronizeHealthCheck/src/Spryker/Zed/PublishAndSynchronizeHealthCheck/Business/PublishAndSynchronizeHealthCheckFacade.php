<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckBusinessFactory getFactory()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckRepositoryInterface getRepository()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckEntityManagerInterface getEntityManager()
 */
class PublishAndSynchronizeHealthCheckFacade extends AbstractFacade implements PublishAndSynchronizeHealthCheckFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function savePublishAndSynchronizeHealthCheckEntity(): PublishAndSynchronizeHealthCheckTransfer
    {
        return $this->getFactory()->createPublishAndSynchronizeHealthCheckDataWriter()->savePublishAndSynchronizeHealthCheckEntity();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function getPublishAndSynchronizeHealthCheckCollection(
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer {
        return $this->getRepository()->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer);
    }
}
