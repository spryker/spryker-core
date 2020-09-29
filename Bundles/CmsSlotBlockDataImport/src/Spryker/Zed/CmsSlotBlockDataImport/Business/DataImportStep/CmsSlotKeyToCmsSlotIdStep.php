<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotKeyToCmsSlotIdStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idCmsSlotBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CmsSlotBlockDataSetInterface::COL_SLOT_ID] = $this->getIdCmsSlotByKey(
            $dataSet[CmsSlotBlockDataSetInterface::COL_SLOT_KEY]
        );
    }

    /**
     * @param string $cmsSlotKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCmsSlotByKey(string $cmsSlotKey): int
    {
        if (isset($this->idCmsSlotBuffer[$cmsSlotKey])) {
            return $this->idCmsSlotBuffer[$cmsSlotKey];
        }

        /** @var int|null $idCmsSlot */
        $idCmsSlot = SpyCmsSlotQuery::create()
            ->filterByKey($cmsSlotKey)
            ->select([SpyCmsSlotTableMap::COL_ID_CMS_SLOT])
            ->findOne();

        if (!$idCmsSlot) {
            throw new EntityNotFoundException(sprintf('Could not find CMS slot ID by key "%s".', $cmsSlotKey));
        }

        $this->idCmsSlotBuffer[$cmsSlotKey] = $idCmsSlot;

        return $idCmsSlot;
    }
}
