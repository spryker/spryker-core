<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsBlockNameToCmsBlockIdStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCmsBlockCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_ID] = $this->getIdCmsBlockByName(
            $dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_NAME]
        );
    }

    /**
     * @param string $cmsBlockName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCmsBlockByName(string $cmsBlockName): int
    {
        if (isset($this->idCmsBlockCache[$cmsBlockName])) {
            return $this->idCmsBlockCache[$cmsBlockName];
        }

        $idCmsBlock = SpyCmsBlockQuery::create()
            ->filterByName($cmsBlockName)
            ->select([SpyCmsBlockTableMap::COL_ID_CMS_BLOCK])
            ->findOne();

        if (!$idCmsBlock) {
            throw new EntityNotFoundException(sprintf('Could not find CMS Block ID by name "%s".', $cmsBlockName));
        }

        $this->idCmsBlockCache[$cmsBlockName] = $idCmsBlock;

        return $idCmsBlock;
    }
}
