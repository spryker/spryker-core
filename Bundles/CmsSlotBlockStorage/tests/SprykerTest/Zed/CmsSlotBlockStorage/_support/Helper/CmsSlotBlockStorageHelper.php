<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlockStorage\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SpyCmsSlotBlockStorageEntityTransfer;
use Generated\Shared\Transfer\SpyCmsSlotToCmsSlotTemplateEntityTransfer;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplate;
use Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorage;
use Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorageQuery;

class CmsSlotBlockStorageHelper extends Module
{
    /**
     * @param array|null $seedData
     *
     * @return \Generated\Shared\Transfer\SpyCmsSlotBlockStorageEntityTransfer
     */
    public function hasCmsSlotBlockStorage(?array $seedData = []): SpyCmsSlotBlockStorageEntityTransfer
    {
        $defaultData = [
            SpyCmsSlotBlockStorageEntityTransfer::SLOT_TEMPLATE_KEY => 'template-path:slot-key-1',
            SpyCmsSlotBlockStorageEntityTransfer::DATA => '',
            SpyCmsSlotBlockStorageEntityTransfer::FK_CMS_SLOT => 1,
            SpyCmsSlotBlockStorageEntityTransfer::FK_CMS_SLOT_TEMPLATE => 1,
        ];

        $cmsSlotBlockStorageEntityTransfer = (new SpyCmsSlotBlockStorageEntityTransfer())
            ->fromArray($seedData + $defaultData);

        $cmsSlotBlockStorageEntity = new SpyCmsSlotBlockStorage();
        $cmsSlotBlockStorageEntity->fromArray($cmsSlotBlockStorageEntityTransfer->toArray());
        $cmsSlotBlockStorageEntity->setIsSendingToQueue(false);
        $cmsSlotBlockStorageEntity->save();

        $cmsSlotBlockStorageEntityTransfer->fromArray($cmsSlotBlockStorageEntity->toArray(), true);

        return $cmsSlotBlockStorageEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsSlotToCmsSlotTemplateEntityTransfer $cmsSlotToCmsSlotTemplateEntityTransfer
     *
     * @return void
     */
    public function haveCmsSlotToCmsSlotTemplateInDb(
        SpyCmsSlotToCmsSlotTemplateEntityTransfer $cmsSlotToCmsSlotTemplateEntityTransfer
    ): void {
        $cmsSlotToCmsSlotTemplateEntity = new SpyCmsSlotToCmsSlotTemplate();
        $cmsSlotToCmsSlotTemplateEntity->fromArray($cmsSlotToCmsSlotTemplateEntityTransfer->toArray());
        $cmsSlotToCmsSlotTemplateEntity->save();
    }

    /**
     * @return void
     */
    public function ensureCmsSlotBlockStorageTableIsEmpty(): void
    {
        $cmsSlotBlockStorageQuery = $this->getCmsSlotBlockStorageQuery();
        $cmsSlotBlockStorageQuery->deleteAll();
    }

    /**
     * @return \Orm\Zed\CmsSlotBlockStorage\Persistence\SpyCmsSlotBlockStorageQuery
     */
    public function getCmsSlotBlockStorageQuery(): SpyCmsSlotBlockStorageQuery
    {
        return SpyCmsSlotBlockStorageQuery::create();
    }
}
