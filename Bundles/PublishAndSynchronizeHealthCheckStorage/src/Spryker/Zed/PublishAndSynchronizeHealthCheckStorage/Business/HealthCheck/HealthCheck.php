<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\HealthCheck;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Spryker\Shared\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig as PublishAndSynchronizeHealthCheckStoragePublishAndSynchronizeHealthCheckStorageConfig;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig;

class HealthCheck implements HealthCheckInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig
     */
    protected $publishAndSynchronizeHealthCheckStorageConfig;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Client\PublishAndSynchronizeHealthCheckStorageToStorageClientInterface $storageClient
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig $publishAndSynchronizeHealthCheckStorageConfig
     */
    public function __construct(
        PublishAndSynchronizeHealthCheckStorageToStorageClientInterface $storageClient,
        PublishAndSynchronizeHealthCheckStorageConfig $publishAndSynchronizeHealthCheckStorageConfig
    ) {
        $this->storageClient = $storageClient;
        $this->publishAndSynchronizeHealthCheckStorageConfig = $publishAndSynchronizeHealthCheckStorageConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function performHealthCheck(): HealthCheckServiceResponseTransfer
    {
        $healthCheckServiceResponseTransfer = new HealthCheckServiceResponseTransfer();
        $healthCheckServiceResponseTransfer
            ->setName('P&S storage health check')
            ->setStatus(false);

        $publishAndSynchronizeHealthCheckStorageData = $this->storageClient->get(
            PublishAndSynchronizeHealthCheckStoragePublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_KEY
        );

        if ($publishAndSynchronizeHealthCheckStorageData === null) {
            return $this->failedResponse($healthCheckServiceResponseTransfer, 'Could not find the expected data for the key "%s" in the storage.');
        }

        if (!$this->isValid($publishAndSynchronizeHealthCheckStorageData)) {
            return $this->failedResponse($healthCheckServiceResponseTransfer, 'The data for the key "%s" in the storage is older than expected.');
        }

        $healthCheckServiceResponseTransfer->setStatus(true);

        return $healthCheckServiceResponseTransfer;
    }

    /**
     * @param array $publishAndSynchronizeHealthCheckStorageData
     *
     * @return bool
     */
    protected function isValid(array $publishAndSynchronizeHealthCheckStorageData): bool
    {
        $dateInterval = DateInterval::createFromDateString($this->publishAndSynchronizeHealthCheckStorageConfig->getValidationThreshold());
        $now = new DateTime();
        $maxAge = $now->sub($dateInterval);

        $storageDataUpdatedAt = new DateTime($publishAndSynchronizeHealthCheckStorageData['updated_at']);

        if ($maxAge > $storageDataUpdatedAt) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer $healthCheckServiceResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    protected function failedResponse(
        HealthCheckServiceResponseTransfer $healthCheckServiceResponseTransfer,
        string $message
    ): HealthCheckServiceResponseTransfer {
        $healthCheckServiceResponseTransfer->setMessage(sprintf(
            $message,
            PublishAndSynchronizeHealthCheckStoragePublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_STORAGE_KEY
        ));

        return $healthCheckServiceResponseTransfer;
    }
}
