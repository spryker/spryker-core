<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;

class PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeBridge implements PublishAndSynchronizeHealthCheckStorageToPublishAndSynchronizeHealthCheckFacadeInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface
     */
    protected $publishAndSynchronizeHealthCheckFacade;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface $publishAndSynchronizeHealthCheckFacade
     */
    public function __construct($publishAndSynchronizeHealthCheckFacade)
    {
        $this->publishAndSynchronizeHealthCheckFacade = $publishAndSynchronizeHealthCheckFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCollectionTransfer
     */
    public function getPublishAndSynchronizeHealthCheckCollection(
        PublishAndSynchronizeHealthCheckCriteriaTransfer $publishAndSynchronizeHealthCheckCriteriaTransfer
    ): PublishAndSynchronizeHealthCheckCollectionTransfer {
        return $this->publishAndSynchronizeHealthCheckFacade->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer);
    }
}
