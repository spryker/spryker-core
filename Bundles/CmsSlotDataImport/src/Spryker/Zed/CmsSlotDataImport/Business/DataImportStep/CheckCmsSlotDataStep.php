<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotDataSetInterface;
use Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckCmsSlotDataStep extends AbstractCheckDataStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @param \Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface $cmsSlotFacade
     */
    public function __construct(CmsSlotDataImportToCmsSlotFacadeInterface $cmsSlotFacade)
    {
        $this->cmsSlotFacade = $cmsSlotFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsSlotTransfer = (new CmsSlotTransfer())
            ->setKey($dataSet[CmsSlotDataSetInterface::CMS_SLOT_KEY])
            ->setContentProviderType($dataSet[CmsSlotDataSetInterface::CMS_SLOT_CONTENT_PROVIDER])
            ->setName($dataSet[CmsSlotDataSetInterface::CMS_SLOT_NAME])
            ->setDescription($dataSet[CmsSlotDataSetInterface::CMS_SLOT_DESCRIPTION])
            ->setIsActive($dataSet[CmsSlotDataSetInterface::CMS_SLOT_IS_ACTIVE]);

        $validationResult = $this->cmsSlotFacade->validateCmsSlot($cmsSlotTransfer);

        if (!$validationResult->getIsSuccess()) {
            $errorMessages = $this->getErrorMessages($validationResult);

            throw new InvalidDataException(
                sprintf(
                    "Failed to import cms slot with key [%s]: \n%s",
                    $cmsSlotTransfer->getKey(),
                    implode("\n", $errorMessages)
                )
            );
        }
    }
}
