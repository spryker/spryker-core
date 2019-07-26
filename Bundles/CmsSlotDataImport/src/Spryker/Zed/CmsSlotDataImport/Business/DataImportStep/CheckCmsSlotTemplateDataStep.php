<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Spryker\Zed\CmsSlotDataImport\Business\DataSet\CmsSlotTemplateDataSetInterface;
use Spryker\Zed\CmsSlotDataImport\Dependency\Facade\CmsSlotDataImportToCmsSlotFacadeInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckCmsSlotTemplateDataStep implements DataImportStepInterface
{
    protected const MESSAGE_INVALID_DATA_EXCEPTION = 'Failed to import cms slot template with path [%s]: %s';
    protected const MESSAGE_PROPERTY_ERROR = '"%s" property: %s';

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
                    static::MESSAGE_INVALID_DATA_EXCEPTION,
                    $cmsSlotTemplateTransfer->getPath(),
                    implode(';', $errorMessages)
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataObjectValidationResponseTransfer $dataObjectValidationResponseTransfer
     *
     * @return string[]
     */
    protected function getErrorMessages(DataObjectValidationResponseTransfer $dataObjectValidationResponseTransfer): array
    {
        $messages = [];

        foreach ($dataObjectValidationResponseTransfer->getValidationResults() as $validationResult) {
            foreach ($validationResult->getMessages() as $propertyValidationResultMessage) {
                $messages[] = sprintf(
                    static::MESSAGE_PROPERTY_ERROR,
                    $validationResult->getPropertyName(),
                    $propertyValidationResultMessage->getValue()
                );
            }
        }

        return $messages;
    }
}
