<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer;
use Spryker\Shared\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\PublishAndSynchronizeHealthCheckStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\PublishAndSynchronizeHealthCheckStorageConfig getConfig()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Communication\PublishAndSynchronizeHealthCheckStorageCommunicationFactory getFactory()
 */
class PublishAndSynchronizeHealthCheckPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\PublishAndSynchronizeHealthCheck\Persistence\Map\SpyPublishAndSynchronizeHealthCheckTableMap::COL_ID_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK
     *
     * @var string
     */
    protected const COL_ID_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK = 'spy_publish_and_synchronize_health_check.id_publish_and_synchronize_health_check';

    /**
     * {@inheritDoc}
     * - Retrieves publish and synchronize health check collection by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $publishAndSynchronizeHealthCheckCriteriaTransfer = $this->createPublishAndSynchronizeHealthCheckCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getPublishAndSynchronizeHealthCheckFacade()
            ->getPublishAndSynchronizeHealthCheckCollection($publishAndSynchronizeHealthCheckCriteriaTransfer)
            ->getPublishAndSynchronizeHealthChecks()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return PublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return PublishAndSynchronizeHealthCheckStorageConfig::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckCriteriaTransfer
     */
    protected function createPublishAndSynchronizeHealthCheckCriteriaTransfer(int $offset, int $limit): PublishAndSynchronizeHealthCheckCriteriaTransfer
    {
        return (new PublishAndSynchronizeHealthCheckCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
            );
    }
}
