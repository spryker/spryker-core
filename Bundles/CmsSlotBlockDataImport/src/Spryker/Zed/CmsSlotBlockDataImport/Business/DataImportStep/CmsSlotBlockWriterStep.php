<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Spryker\Zed\CmsSlotBlock\Dependency\CmsSlotBlockEvents;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

class CmsSlotBlockWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(DataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsSlotBlockEntity = SpyCmsSlotBlockQuery::create()
            ->filterByFkCmsSlot($dataSet[CmsSlotBlockDataSetInterface::COL_SLOT_ID])
            ->filterByFkCmsBlock($dataSet[CmsSlotBlockDataSetInterface::COL_BLOCK_ID])
            ->filterByFkCmsSlotTemplate($dataSet[CmsSlotBlockDataSetInterface::COL_SLOT_TEMPLATE_ID])
            ->findOneOrCreate();

        $cmsSlotBlockEntity->setPosition($dataSet[CmsSlotBlockDataSetInterface::COL_POSITION]);
        $conditions = $this->utilEncodingService->encodeJson(
            $dataSet[CmsSlotBlockDataSetInterface::COL_CONDITIONS_ARRAY] ?? [],
        );
        $cmsSlotBlockEntity->setConditions($conditions);

        $cmsSlotBlockEntity->save();

        $this->addPublishEvents(
            CmsSlotBlockEvents::CMS_SLOT_BLOCK_PUBLISH,
            (int)$cmsSlotBlockEntity->getIdCmsSlotBlock(),
        );
    }
}
