<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Hook;

use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\Map\SpyCmsSlotBlockTableMap;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;

class CmsSlotBlockDataImportAfterImportHook implements DataImporterAfterImportInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     */
    public function __construct(DataImportToEventFacadeInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @return void
     */
    public function afterImport(): void
    {
        $cmsSlotBlockIds = SpyCmsSlotBlockQuery::create()
            ->select(SpyCmsSlotBlockTableMap::COL_ID_CMS_SLOT_BLOCK)
            ->find()
            ->toArray();

        if (count($cmsSlotBlockIds) === 0) {
            return;
        }

        $eventTransfers = $this->mapCmsSlotBlockIdsToEventTransfers($cmsSlotBlockIds);
        $this->eventFacade->triggerBulk(CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH, $eventTransfers);
    }

    /**
     * @param int[] $cmsSlotBlockIds
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    protected function mapCmsSlotBlockIdsToEventTransfers(array $cmsSlotBlockIds): array
    {
        $eventTransfers = [];

        foreach ($cmsSlotBlockIds as $idCmsSlotBlock) {
            $eventTransfers[] = (new EventEntityTransfer())
                ->setId($idCmsSlotBlock);
        }

        return $eventTransfers;
    }
}
