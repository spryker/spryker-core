<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListCheckContentDataStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentInterface $contentFacade
     */
    public function __construct(ContentProductDataImportToContentInterface $contentFacade)
    {
        $this->contentFacade = $contentFacade;
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
        $contentTransfer = (new ContentTransfer())
            ->setName($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_NAME])
            ->setDescription($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DESCRIPTION]);

        $validationResult = $this->contentFacade->validateContent($contentTransfer);

        if (!$validationResult->getIsSuccess()) {
            $errorMessages = $this->getErrorMessages($validationResult);
            $errorMessage = implode('; ', $errorMessages);

            throw new InvalidDataException($errorMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentValidationResponseTransfer $contentValidationResponseTransfer
     *
     * @return array
     */
    protected function getErrorMessages(ContentValidationResponseTransfer $contentValidationResponseTransfer): array
    {
        $messages = [];

        foreach ($contentValidationResponseTransfer->getParameterMessages() as $contentParameterMessageTransfer) {
            foreach ($contentParameterMessageTransfer->getMessages() as $messageTransfer) {
                $messages[] = '[' . $contentParameterMessageTransfer->getParameter() . '] ' . $messageTransfer->getValue();
            }
        }

        return $messages;
    }
}
