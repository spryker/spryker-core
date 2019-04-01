<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBannerDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentBannerDataImport\Business\Model\DataSet\ContentBannerDataSetInterface;
use Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckLocalizedItemsStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface
     */
    protected $contentBanner;

    private const ERROR_MESSAGE = 'Failed to import locale id [%s]: %s';

    /**
     * @param \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface $contentBanner
     */
    public function __construct(ContentBannerDataImportToContentBannerInterface $contentBanner)
    {
        $this->contentBanner = $contentBanner;
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
        $validatedItems = [];
        foreach ($dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS] as $idLocale => $attributes) {
            $validationResult = $this->contentBanner->validateContentBannerTerm($attributes);

            if (!$validationResult->getIsSuccess()) {
                $errorMessages = $this->getErrorMessages($validationResult);

                throw new InvalidDataException(
                    sprintf(
                        static::ERROR_MESSAGE,
                        $idLocale,
                        implode(';', $errorMessages)
                    )
                );
            }
            $validatedItems[$idLocale] = $attributes;
        }

        $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_ITEMS] = $validatedItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentValidationResponseTransfer $contentValidationResponseTransfer
     *
     * @return array
     */
    private function getErrorMessages(ContentValidationResponseTransfer $contentValidationResponseTransfer): array
    {
        $messages = [];
        foreach ($contentValidationResponseTransfer->getParameterMessages() as $parameterMessages) {
            foreach ($parameterMessages->getMessages() as $parameterMessage) {
                $messages[] = '[' . $parameterMessages->getParameter() . '] ' . $parameterMessage->getValue();
            }
        }

        return $messages;
    }
}
