<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\Base\SpyCmsSlotTemplateQuery;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class TemplatePathToCmsSlotTemplateIdStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCmsSlotTemplateCache;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotDataSetInterface::CMS_SLOT_TEMPLATE_ID] = $this->getIdCmsSlotTemplateByTemplatePath(
            $dataSet[CmsSlotDataSetInterface::CMS_SLOT_TEMPLATE_PATH]
        );
    }

    /**
     * @param string $templatePath
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCmsSlotTemplateByTemplatePath(string $templatePath): int
    {
        if (isset($this->idCmsSlotTemplateCache[$templatePath])) {
            return $this->idCmsSlotTemplateCache[$templatePath];
        }

        /** @var int $idCmsSlotTemplate */
        $idCmsSlotTemplate = SpyCmsSlotTemplateQuery::create()
            ->filterByPath($templatePath)
            ->select([SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE])
            ->findOne();

        if (!$idCmsSlotTemplate) {
            throw new EntityNotFoundException(sprintf('Could not find template by template path "%s".', $templatePath));
        }

        $this->idCmsSlotTemplateCache[$templatePath] = $idCmsSlotTemplate;

        return $idCmsSlotTemplate;
    }
}
