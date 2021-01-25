<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Business\Step;

use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\ContentNavigationDataImport\Business\DataSet\ContentNavigationDataSetInterface;
use Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CheckLocalizedContentNavigationTermStep implements DataImportStepInterface
{
    protected const ERROR_MESSAGE = 'Failed to import locale id [%s]: %s';

    /**
     * @var \Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeInterface
     */
    protected $contentNavigationFacade;

    /**
     * @param \Spryker\Zed\ContentNavigationDataImport\Dependency\Facade\ContentNavigationDataImportToContentNavigationFacadeInterface $contentNavigationFacade
     */
    public function __construct(ContentNavigationDataImportToContentNavigationFacadeInterface $contentNavigationFacade)
    {
        $this->contentNavigationFacade = $contentNavigationFacade;
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
        $validatedContentNavigationTerms = [];
        $defaultLocaleIsPresent = false;

        /**
         * @var \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
         */
        foreach ($dataSet[ContentNavigationDataSetInterface::CONTENT_LOCALIZED_NAVIGATION_TERMS] as $idLocale => $contentNavigationTermTransfer) {
            if (!$idLocale) {
                $defaultLocaleIsPresent = true;
            }

            $contentValidationResponseTransfer = $this->contentNavigationFacade->validateContentNavigationTerm($contentNavigationTermTransfer);

            if (!$contentValidationResponseTransfer->getIsSuccess()) {
                $errorMessages = $this->getErrorMessages($contentValidationResponseTransfer);

                throw new InvalidDataException(
                    sprintf(
                        static::ERROR_MESSAGE,
                        $idLocale,
                        implode(';', $errorMessages)
                    )
                );
            }
            $validatedContentNavigationTerms[$idLocale] = $contentNavigationTermTransfer;
        }

        if (!$defaultLocaleIsPresent) {
            throw new InvalidDataException('Default locale is required.');
        }

        $dataSet[ContentNavigationDataSetInterface::CONTENT_LOCALIZED_NAVIGATION_TERMS] = $validatedContentNavigationTerms;
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
