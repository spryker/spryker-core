<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsBlockKeyToCmsBlockIdStep implements DataImportStepInterface
{
    /**
     * @var array<int>
     */
    protected $idCmsBlockBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotBlockDataSetInterface::COL_BLOCK_ID] = $this->getIdCmsBlockByKey(
            $dataSet[CmsSlotBlockDataSetInterface::COL_BLOCK_KEY],
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

        /** @var int|null $idCmsBlock */
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
