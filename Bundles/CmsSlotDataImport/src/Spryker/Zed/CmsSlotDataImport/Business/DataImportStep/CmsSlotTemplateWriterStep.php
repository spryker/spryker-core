<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\Base\SpyCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotTemplateDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotTemplateWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsSlotTemplateEntity = SpyCmsSlotTemplateQuery::create()
            ->filterByPath($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_TEMPLATE_PATH])
            ->findOneOrCreate();

        $cmsSlotTemplateEntity
            ->setPath($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_TEMPLATE_PATH])
            ->setName($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_NAME])
            ->setDescription($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_DESCRIPTION]);

        $cmsSlotTemplateEntity->save();
    }
}
