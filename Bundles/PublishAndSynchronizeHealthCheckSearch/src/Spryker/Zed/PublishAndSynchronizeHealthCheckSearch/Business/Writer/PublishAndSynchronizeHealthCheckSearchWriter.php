<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Business\Writer;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface;

class PublishAndSynchronizeHealthCheckSearchWriter implements PublishAndSynchronizeHealthCheckSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface
     */
    protected $publishAndSynchronizeHealthCheckSearchRepository;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Dependency\Facade\PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\PublishAndSynchronizeHealthCheckSearchRepositoryInterface $publishAndSynchronizeHealthCheckSearchRepository
     */
    public function __construct(
        PublishAndSynchronizeHealthCheckSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        PublishAndSynchronizeHealthCheckSearchRepositoryInterface $publishAndSynchronizeHealthCheckSearchRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->publishAndSynchronizeHealthCheckSearchRepository = $publishAndSynchronizeHealthCheckSearchRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writePublishAndSynchronizeHealthCheckSearchCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void
    {
        $publishAndSynchronizeHealthCheckSearchIds = array_filter(
            $this->eventBehaviorFacade->getEventTransferIds($eventTransfers),
        );

        $this->writerPublishAndSynchronizeHealthCheckSearchCollection($publishAndSynchronizeHealthCheckSearchIds);
    }

    /**
     * @param array<int> $publishAndSynchronizeHealthCheckSearchIds
     *
     * @return void
     */
    protected function writerPublishAndSynchronizeHealthCheckSearchCollection(array $publishAndSynchronizeHealthCheckSearchIds): void
    {
        foreach ($publishAndSynchronizeHealthCheckSearchIds as $publishAndSynchronizeHealthCheckSearchId) {
            $publishAndSynchronizeHealthCheckSearchEntity = $this->publishAndSynchronizeHealthCheckSearchRepository->findOrCreatePublishAndSynchronizeHealthCheckSearchByIdPublishAndSynchronizeHealthCheck($publishAndSynchronizeHealthCheckSearchId);
            $publishAndSynchronizeHealthCheckTransfer = $this->publishAndSynchronizeHealthCheckSearchRepository->getPublishAndSynchronizeHealthCheckTransferByIdPublishAndSynchronizeHealthCheck($publishAndSynchronizeHealthCheckSearchId);

            $this->storeData($publishAndSynchronizeHealthCheckSearchEntity, $publishAndSynchronizeHealthCheckTransfer);
        }
    }

    /**
     * @param \Orm\Zed\PublishAndSynchronizeHealthCheckSearch\Persistence\SpyPublishAndSynchronizeHealthCheckSearch $publishAndSynchronizeHealthCheckSearchEntity
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
     *
     * @return void
     */
    protected function storeData(
        SpyPublishAndSynchronizeHealthCheckSearch $publishAndSynchronizeHealthCheckSearchEntity,
        PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
    ) {
        $publishAndSynchronizeHealthCheckSearchEntity->fromArray($publishAndSynchronizeHealthCheckTransfer->toArray());
        $publishAndSynchronizeHealthCheckSearchEntity->setData($publishAndSynchronizeHealthCheckTransfer->toArray());
        $publishAndSynchronizeHealthCheckSearchEntity->save();
    }
}
