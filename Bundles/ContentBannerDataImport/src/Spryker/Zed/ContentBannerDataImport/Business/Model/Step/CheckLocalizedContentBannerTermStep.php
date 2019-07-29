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

class CheckLocalizedContentBannerTermStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ContentBannerDataImport\Dependency\Facade\ContentBannerDataImportToContentBannerInterface
     */
    protected $contentBanner;

    protected const ERROR_MESSAGE = 'Failed to import locale id [%s]: %s';

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
        $validatedContentBannerTerms = [];
        $defaultLocaleIsPresent = false;
        foreach ($dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_BANNER_TERMS] as $idLocale => $contentBannerTerm) {
            if (!$idLocale) {
                $defaultLocaleIsPresent = true;
            }

            $validationResult = $this->contentBanner->validateContentBannerTerm($contentBannerTerm);

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
            $validatedContentBannerTerms[$idLocale] = $contentBannerTerm;
        }

        if (!$defaultLocaleIsPresent) {
            throw new InvalidDataException('Default locale is required.');
        }

        $dataSet[ContentBannerDataSetInterface::CONTENT_LOCALIZED_BANNER_TERMS] = $validatedContentBannerTerms;
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
            foreach ($parameterMessages->getMessages() as $parameterMessage) {
                $messages[] = '[' . $parameterMessages->getParameter() . '] ' . $parameterMessage->getValue();
            }
        }

        return $messages;
    }
}
