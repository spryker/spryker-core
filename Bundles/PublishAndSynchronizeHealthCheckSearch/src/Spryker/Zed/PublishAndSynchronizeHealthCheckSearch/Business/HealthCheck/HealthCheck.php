<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\HealthCheck;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Shared\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig as SharedPublishAndSynchronizeHealthCheckSearchConfig;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig;

class HealthCheck implements HealthCheckInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig
     */
    protected $publishAndSynchronizeHealthCheckSearchConfig;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Client\PublishAndSynchronizeHealthCheckSearchToSearchClientInterface $searchClient
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\PublishAndSynchronizeHealthCheckSearchConfig $publishAndSynchronizeHealthCheckSearchConfig
     */
    public function __construct(
        PublishAndSynchronizeHealthCheckSearchToSearchClientInterface $searchClient,
        PublishAndSynchronizeHealthCheckSearchConfig $publishAndSynchronizeHealthCheckSearchConfig
    ) {
        $this->searchClient = $searchClient;
        $this->publishAndSynchronizeHealthCheckSearchConfig = $publishAndSynchronizeHealthCheckSearchConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function performHealthCheck(): HealthCheckServiceResponseTransfer
    {
        $healthCheckServiceResponseTransfer = new HealthCheckServiceResponseTransfer();
        $healthCheckServiceResponseTransfer
            ->setName('P&S search health check')
            ->setStatus(false);

        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(PublishAndSynchronizeHealthCheckSearchConfig::SOURCE_IDENTIFIER);

        $searchDocumentTransfer = new SearchDocumentTransfer();
        $searchDocumentTransfer->setId(SharedPublishAndSynchronizeHealthCheckSearchConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_ID);
        $searchDocumentTransfer->setSearchContext($searchContextTransfer);

        $searchDocumentTransfer = $this->searchClient->readDocument($searchDocumentTransfer);

        if (empty($searchDocumentTransfer->getData())) {
            return $this->failedResponse($healthCheckServiceResponseTransfer, 'Could not find the expected data for the key "%s" in the search.');
        }

        if (!$this->isValid($searchDocumentTransfer)) {
            return $this->failedResponse($healthCheckServiceResponseTransfer, 'The data for the key "%s" in the search is older than expected.');
        }

        $healthCheckServiceResponseTransfer->setStatus(true);

        return $healthCheckServiceResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    protected function isValid(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $dateInterval = DateInterval::createFromDateString($this->publishAndSynchronizeHealthCheckSearchConfig->getValidationThreshold());
        $now = new DateTime();
        $maxAge = $now->sub($dateInterval);

        $searchDataUpdatedAt = new DateTime($searchDocumentTransfer->getData()['updated_at']);

        if ($maxAge > $searchDataUpdatedAt) {
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
            SharedPublishAndSynchronizeHealthCheckSearchConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SEARCH_ID
        ));

        return $healthCheckServiceResponseTransfer;
    }
}
