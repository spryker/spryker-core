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
    protected $idCmsBlockBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_ID] = $this->getIdCmsBlockByKey(
            $dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_KEY]
        );
    }

    /**
     * @param string $cmsBlockKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCmsBlockByKey(string $cmsBlockKey): int
    {
        if (isset($this->idCmsBlockBuffer[$cmsBlockKey])) {
            return $this->idCmsBlockBuffer[$cmsBlockKey];
        }

        $idCmsBlock = SpyCmsBlockQuery::create()
            ->filterByKey($cmsBlockKey)
            ->select([SpyCmsBlockTableMap::COL_ID_CMS_BLOCK])
            ->findOne();

        if (!$idCmsBlock) {
            throw new EntityNotFoundException(sprintf('Could not find CMS Block ID by key "%s".', $cmsBlockKey));
        }

        $this->idCmsBlockBuffer[$cmsBlockKey] = $idCmsBlock;

        return $idCmsBlock;
    }
}
