<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Orm\Zed\CmsSlot\Persistence\Base\SpyCmsSlotTemplateQuery;
use Spryker\Service\UtilText\Model\Hash;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotTemplateDataSetInterface;
use Spryker\Zed\CmsSlotDataImport\Dependency\Service\CmsSlotDataImportToUtilTextServiceInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotTemplateWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotDataImport\Dependency\Service\CmsSlotDataImportToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\CmsSlotDataImport\Dependency\Service\CmsSlotDataImportToUtilTextServiceInterface $utilTextService
     */
    public function __construct(CmsSlotDataImportToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

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

        $pathHash = $this->utilTextService->hashValue(
            $dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_TEMPLATE_PATH],
            Hash::MD5
        );

        $cmsSlotTemplateEntity
            ->setPath($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_TEMPLATE_PATH])
            ->setPathHash($pathHash)
            ->setName($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_NAME])
            ->setDescription($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_DESCRIPTION]);

        $cmsSlotTemplateEntity->save();
    }
}
