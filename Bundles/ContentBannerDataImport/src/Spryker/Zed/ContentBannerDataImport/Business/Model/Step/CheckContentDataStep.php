<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckContentDataStep implements DataImportStepInterface
{
    protected const ERROR_MESSAGE = 'Failed to import content banner: %s';

    /**
     * @var \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentInterface $contentFacade
     */
    public function __construct(ContentBannerDataImportToContentInterface $contentFacade)
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
            ->setName($dataSet[ContentTransfer::NAME])
            ->setDescription($dataSet[ContentTransfer::DESCRIPTION])
            ->setKey($dataSet[ContentTransfer::KEY]);

        $validationResult = $this->contentFacade->validateContent($contentTransfer);

        if (!$validationResult->getIsSuccess()) {
            $errorMessages = $this->getErrorMessages($validationResult);

            throw new InvalidDataException(
                sprintf(
                    static::ERROR_MESSAGE,
                    implode(';', $errorMessages)
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentValidationResponseTransfer $contentValidationResponseTransfer
     *
     * @return string[]
     */
    protected function getErrorMessages(ContentValidationResponseTransfer $contentValidationResponseTransfer): array
    {
        $messages = [];
        foreach ($contentValidationResponseTransfer->getParameterMessages() as $parameterMessages) {
            foreach ($parameterMessages->getMessages() as $message) {
                $messages[] = '[' . $parameterMessages->getParameter() . '] ' . $message->getValue();
            }
        }

        return $messages;
    }
}
