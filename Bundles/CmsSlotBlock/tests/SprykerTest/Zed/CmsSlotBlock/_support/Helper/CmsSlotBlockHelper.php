<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsSlotBlockBuilder;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;

class CmsSlotBlockHelper extends Module
{
    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer
     */
    public function haveCmsSlotBlockInDb(array $override = []): CmsSlotBlockTransfer
    {
        $data = [
            CmsSlotBlockTransfer::ID_SLOT_TEMPLATE => 1,
            CmsSlotBlockTransfer::ID_SLOT => 1,
            CmsSlotBlockTransfer::ID_CMS_BLOCK => 1,
            CmsSlotBlockTransfer::CONDITIONS => [],
            CmsSlotBlockTransfer::POSITION => 1,
        ];

        $cmsSlotBlockTransfer = (new CmsSlotBlockBuilder(array_merge($data, $override)))->build();

        $cmsSlotBlockEntity = new SpyCmsSlotBlock();
        $cmsSlotBlockEntity->setFkCmsSlotTemplate($cmsSlotBlockTransfer->getIdSlotTemplate());
        $cmsSlotBlockEntity->setFkCmsSlot($cmsSlotBlockTransfer->getIdSlot());
        $cmsSlotBlockEntity->setFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock());
        $cmsSlotBlockEntity->setPosition($cmsSlotBlockTransfer->getPosition());

        $conditions = [];

        foreach ($cmsSlotBlockTransfer->getConditions() as $conditionKey => $cmsSlotBlockConditionTransfer) {
            $conditions[$conditionKey] = $cmsSlotBlockConditionTransfer->modifiedToArray(true, true);
        }

        $cmsSlotBlockEntity->setConditions(json_encode($conditions));
        $cmsSlotBlockEntity->save();

        $cmsSlotBlockTransfer->setIdCmsSlotBlock((string)$cmsSlotBlockEntity->getIdCmsSlotBlock());

        return $cmsSlotBlockTransfer;
    }

    /**
     * @return void
     */
    public function ensureCmsSlotBlockTableIsEmpty(): void
    {
        SpyCmsSlotBlockQuery::create()->deleteAll();
    }
}
