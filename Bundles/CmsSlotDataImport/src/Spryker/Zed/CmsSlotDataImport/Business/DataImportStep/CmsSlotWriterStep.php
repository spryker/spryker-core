<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplate;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlot\Dependency\CmsSlotEvents;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsSlotEntity = SpyCmsSlotQuery::create()
            ->filterByKey($dataSet[CmsSlotDataSetInterface::CMS_SLOT_KEY])
            ->findOneOrCreate();

        $cmsSlotEntity
            ->setKey($dataSet[CmsSlotDataSetInterface::CMS_SLOT_KEY])
            ->setContentProviderType($dataSet[CmsSlotDataSetInterface::CMS_SLOT_CONTENT_PROVIDER])
            ->setName($dataSet[CmsSlotDataSetInterface::CMS_SLOT_NAME])
            ->setDescription($dataSet[CmsSlotDataSetInterface::CMS_SLOT_DESCRIPTION])
            ->setIsActive($dataSet[CmsSlotDataSetInterface::CMS_SLOT_IS_ACTIVE]);

        $cmsSlotEntity->save();

        SpyCmsSlotToCmsSlotTemplateQuery::create()->filterByFkCmsSlot($cmsSlotEntity->getIdCmsSlot())->delete();

        $cmsSlotToCmsSlotTemplateEntity = (new SpyCmsSlotToCmsSlotTemplate())
            ->setFkCmsSlot($cmsSlotEntity->getIdCmsSlot())
            ->setFkCmsSlotTemplate($dataSet[CmsSlotDataSetInterface::CMS_SLOT_TEMPLATE_ID]);

        $cmsSlotToCmsSlotTemplateEntity->save();

        $this->addPublishEvents(CmsSlotEvents::CMS_SLOT_PUBLISH, $cmsSlotEntity->getIdCmsSlot());
    }
}
