<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotTemplateDataSetInterface;
use Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckCmsSlotTemplateDataStep extends AbstractCheckDataStep implements DataImportStepInterface
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
        $cmsSlotTemplateTransfer = (new CmsSlotTemplateTransfer())
            ->setPath($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_TEMPLATE_PATH])
            ->setName($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_NAME])
            ->setDescription($dataSet[CmsSlotTemplateDataSetInterface::CMS_SLOT_TEMPLATE_DESCRIPTION]);

        $validationResult = $this->cmsSlotFacade->validateCmsSlotTemplate($cmsSlotTemplateTransfer);

        if (!$validationResult->getIsSuccess()) {
            $errorMessages = $this->getErrorMessages($validationResult);

            throw new InvalidDataException(
                sprintf(
                    "Failed to import cms slot template with path [%s]: \n%s",
                    $cmsSlotTemplateTransfer->getPath(),
                    implode("\n", $errorMessages)
                )
            );
        }
    }
}
