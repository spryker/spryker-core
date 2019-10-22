<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsSlotBlockEntity = SpyCmsSlotBlockQuery::create()
            ->filterByFkCmsSlot($dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_ID])
            ->filterByFkCmsBlock($dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_ID])
            ->filterByFkCmsSlotTemplate($dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_TEMPLATE_ID])
            ->findOneOrCreate();

        $cmsSlotBlockEntity->setPosition($dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_POSITION]);

        $cmsSlotBlockEntity->save();
    }
}
