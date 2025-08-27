<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Exporter;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generator;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\Cms\Dependency\Facade\CmsToEventFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;

class CmsPageEventExporter implements CmsExporterInterface
{
    use LoggerTrait;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface $cmsRepository
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(
        protected CmsToEventFacadeInterface $eventFacade,
        protected CmsRepositoryInterface $cmsRepository,
        protected CmsConfig $cmsConfig
    ) {
    }

    /**
     * @return void
     */
    public function export(): void
    {
        $activeCmsPageIds = $this->cmsRepository->getActiveSearchablePageIds();

        if (!$activeCmsPageIds) {
            $this->getLogger()->debug('No active CMS pages found for export');

            return;
        }

        $this->getLogger()->debug(sprintf('Exporting %d active CMS pages', count($activeCmsPageIds)));

        foreach ($this->getCmsPageIdChunks($activeCmsPageIds) as $cmsPageIds) {
            $eventTransfers = $this->createEventTransfers($cmsPageIds);
            $this->eventFacade->triggerBulk(CmsEvents::ENTITY_SPY_CMS_PAGE_EXPORT, $eventTransfers);
        }
    }

    /**
     * @param array<int> $cmsPageIds
     *
     * @return \Generator
     */
    protected function getCmsPageIdChunks(array $cmsPageIds): Generator
    {
        $chunkSize = max(1, $this->cmsConfig->getCmsPageExportChunkSize());
        foreach (array_chunk($cmsPageIds, $chunkSize) as $chunk) {
            yield $chunk;
        }
    }

    /**
     * @param array<int> $cmsPageIds
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected function createEventTransfers(array $cmsPageIds): array
    {
        return array_map(
            fn ($idCmsPage) => (new EventEntityTransfer())->setId($idCmsPage),
            $cmsPageIds,
        );
    }
}
