<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Business\Writer;

use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorage;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface;

class PublishAndSynchronizeHealthCheckStorageWriter implements PublishAndSynchronizeHealthCheckStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface
     */
    protected $publishAndSynchronizeHealthCheckStorageRepository;

    /**
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Dependency\Facade\PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\PublishAndSynchronizeHealthCheckStorageRepositoryInterface $publishAndSynchronizeHealthCheckStorageRepository
     */
    public function __construct(
        PublishAndSynchronizeHealthCheckStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        PublishAndSynchronizeHealthCheckStorageRepositoryInterface $publishAndSynchronizeHealthCheckStorageRepository
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->publishAndSynchronizeHealthCheckStorageRepository = $publishAndSynchronizeHealthCheckStorageRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writePublishAndSynchronizeHealthCheckStorageCollectionByPublishAndSynchronizeHealthCheckEvents(array $eventTransfers): void
    {
        $publishAndSynchronizeHealthCheckStorageIds = array_filter(
            $this->eventBehaviorFacade->getEventTransferIds($eventTransfers),
        );

        $this->writerPublishAndSynchronizeHealthCheckStorageCollection($publishAndSynchronizeHealthCheckStorageIds);
    }

    /**
     * @param array<int> $publishAndSynchronizeHealthCheckStorageIds
     *
     * @return void
     */
    protected function writerPublishAndSynchronizeHealthCheckStorageCollection(array $publishAndSynchronizeHealthCheckStorageIds): void
    {
        foreach ($publishAndSynchronizeHealthCheckStorageIds as $publishAndSynchronizeHealthCheckStorageId) {
            $publishAndSynchronizeHealthCheckStorageEntity = $this->publishAndSynchronizeHealthCheckStorageRepository->findOrCreatePublishAndSynchronizeHealthCheckStorageByIdPublishAndSynchronizeHealthCheck($publishAndSynchronizeHealthCheckStorageId);
            $publishAndSynchronizeHealthCheckTransfer = $this->publishAndSynchronizeHealthCheckStorageRepository->getPublishAndSynchronizeHealthCheckTransferByIdPublishAndSynchronizeHealthCheck($publishAndSynchronizeHealthCheckStorageId);

            $this->storeData($publishAndSynchronizeHealthCheckStorageEntity, $publishAndSynchronizeHealthCheckTransfer);
        }
    }

    /**
     * @param \Orm\Zed\PublishAndSynchronizeHealthCheckStorage\Persistence\SpyPublishAndSynchronizeHealthCheckStorage $publishAndSynchronizeHealthCheckStorageEntity
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
     *
     * @return void
     */
    protected function storeData(
        SpyPublishAndSynchronizeHealthCheckStorage $publishAndSynchronizeHealthCheckStorageEntity,
        PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
    ) {
        $publishAndSynchronizeHealthCheckStorageEntity->setHealthCheckKey($publishAndSynchronizeHealthCheckTransfer->getHealthCheckKeyOrFail());
        $publishAndSynchronizeHealthCheckStorageEntity->setData($publishAndSynchronizeHealthCheckTransfer->toArray());
        $publishAndSynchronizeHealthCheckStorageEntity->save();
    }
}
