<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Reader;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Exception\PublishAndSynchronizeHealthCheckNotFoundException;
use Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckRepositoryInterface;

class DataReader implements DataReaderInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckRepositoryInterface
     */
    protected $publishAndSynchronizeHealthCheckRepository;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheck\Persistence\PublishAndSynchronizeHealthCheckRepositoryInterface $publishAndSynchronizeHealthCheckRepository
     */
    public function __construct(PublishAndSynchronizeHealthCheckRepositoryInterface $publishAndSynchronizeHealthCheckRepository)
    {
        $this->publishAndSynchronizeHealthCheckRepository = $publishAndSynchronizeHealthCheckRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
     *
     * @throws \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\Exception\PublishAndSynchronizeHealthCheckNotFoundException
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer
     */
    public function getPublishAndSynchronizeHealthCheckTransfer(
        PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
    ): PublishAndSynchronizeHealthCheckTransfer {
        $publishAndSynchronizeHealthCheckTransfer = $this->publishAndSynchronizeHealthCheckRepository
            ->findPublishAndSynchronizeHealthCheckByKey(
                $publishAndSynchronizeHealthCheckTransfer->getHealthCheckKeyOrFail()
            );

        if (!$publishAndSynchronizeHealthCheckTransfer) {
            throw new PublishAndSynchronizeHealthCheckNotFoundException();
        }

        return $publishAndSynchronizeHealthCheckTransfer;
    }
}
