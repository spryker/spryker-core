<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplate;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotToCmsSlotTemplateQuery;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const MESSAGE_MISSING_PATH_ID_EXCEPTION = '';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
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

        if (!isset($dataSet[CmsSlotDataSetInterface::CMS_SLOT_TEMPLATE_ID])) {
            throw new InvalidDataException(sprintf(
                static::MESSAGE_MISSING_PATH_ID_EXCEPTION,
                $dataSet[CmsSlotDataSetInterface::CMS_SLOT_KEY]
            ));
        }

        $existingCmsSlotToCmsSlotTemplateEntities = SpyCmsSlotToCmsSlotTemplateQuery::create()
            ->findByFkCmsSlot($cmsSlotEntity->getIdCmsSlot());

        foreach ($existingCmsSlotToCmsSlotTemplateEntities as $existingCmsSlotToCmsSlotTemplateEntity) {
            $existingCmsSlotToCmsSlotTemplateEntity->delete();
        }

        $idCmsSlotTemplate = $dataSet[CmsSlotDataSetInterface::CMS_SLOT_TEMPLATE_ID];

        $cmsSlotToCmsSlotTemplateEntity = (new SpyCmsSlotToCmsSlotTemplate())
            ->setFkCmsSlot($cmsSlotEntity->getIdCmsSlot())
            ->setFkCmsSlotTemplate($idCmsSlotTemplate);

        $cmsSlotToCmsSlotTemplateEntity->save();
    }
}
